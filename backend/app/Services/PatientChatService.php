<?php

namespace App\Services;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Exception as QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientChatService
{
    public function listConversations(int $patientId, array $filters = []): Collection
    {
        $query = Conversation::query()
            ->with(['participants.user', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn ($q) => $q
                ->where('sender_id', '<>', $patientId)
                ->whereNull('read_at')])
            ->forUser($patientId)
            ->orderByDesc('updated_at');

        if (array_key_exists('archived', $filters)) {
            $query->withParticipantStatus($patientId, (bool) $filters['archived']);
        }

        if (!empty($filters['search'])) {
            $search = Str::lower($filters['search']);
            $query->whereHas('participants.user', function ($q) use ($patientId, $search) {
                $q->where('users.id', '<>', $patientId)
                    ->whereRaw('LOWER(CONCAT(prenom, \' \', name)) LIKE ?', ['%' . $search . '%']);
            });
        }

        return $query->get()->map(fn (Conversation $conversation) => $this->formatConversation($conversation, $patientId));
    }

    public function getConversationForUser(int $conversationId, int $userId): Conversation
    {
        $conversation = Conversation::with(['participants.user'])->find($conversationId);

        if (!$conversation || !$conversation->participants()->where('user_id', $userId)->exists()) {
            throw (new ModelNotFoundException())->setModel(Conversation::class, $conversationId);
        }

        return $conversation;
    }

    public function ensureConversationWithPractitioner(int $patientId, int $practitionerUserId): Conversation
    {
        if ($patientId === $practitionerUserId) {
            throw new \InvalidArgumentException('Impossible de démarrer une conversation avec vous-même.');
        }

        $conversation = Conversation::query()
            ->forUser($patientId)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $practitionerUserId))
            ->first();

        if ($conversation) {
            return $conversation->loadMissing(['participants.user']);
        }

        return DB::transaction(function () use ($patientId, $practitionerUserId) {
            $conversation = Conversation::create();

            $conversation->participants()->createMany([
                [
                    'user_id' => $patientId,
                    'role' => 'PATIENT',
                ],
                [
                    'user_id' => $practitionerUserId,
                    'role' => 'PRATICIEN',
                ],
            ]);

            return $conversation->load(['participants.user']);
        });
    }

    public function presentConversation(Conversation $conversation, int $userId): array
    {
        $conversation->loadMissing([
            'participants.user',
            'messages' => fn ($q) => $q->latest()->limit(1),
        ])->loadCount(['messages as unread_count' => fn ($q) => $q
            ->where('sender_id', '<>', $userId)
            ->whereNull('read_at')]);

        return $this->formatConversation($conversation, $userId);
    }

    public function conversationMessages(Conversation $conversation, int $userId, int $perPage = 30, ?int $beforeId = null): LengthAwarePaginator
    {
        $query = $conversation->messages()
            ->with(['sender', 'attachments'])
            ->orderByDesc('id');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(fn (Message $message) => $this->formatMessage($message, $userId));

        return $paginator;
    }

    public function sendMessage(Conversation $conversation, int $senderId, string $content = null, array $files = []): array
    {
        if (!strlen(trim((string) $content)) && empty($files)) {
            throw new \InvalidArgumentException('Le message ou une pièce jointe est requis.');
        }

        $senderParticipant = $conversation->participantFor($senderId);
        if (!$senderParticipant) {
            throw new \RuntimeException('Utilisateur non autorisé dans la conversation.');
        }

        $message = DB::transaction(function () use ($conversation, $senderId, $content, $files) {
            /** @var Message $message */
            $message = $conversation->messages()->create([
                'sender_id' => $senderId,
                'type' => empty($files) ? 'text' : 'mixed',
                'content' => $content,
            ]);

            foreach ($files as $file) {
                $this->storeAttachment($message, $file);
            }

            $conversation->touch();

            return $message->load(['sender', 'attachments']);
        });

        $this->markConversationAsRead($conversation, $senderId); // le patient vient d'envoyer un message

        $payload = $this->formatMessage($message, $senderId);

        broadcast(new ConversationMessageSent($conversation->id, $payload))->toOthers();

        return $payload;
    }

    public function markConversationAsRead(Conversation $conversation, int $userId): void
    {
        $participant = $conversation->participantFor($userId);

        if (!$participant) {
            return;
        }

        $participant->update(['last_read_at' => now()]);

        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '<>', $userId)
            ->update(['read_at' => now()]);
    }

    public function setArchive(Conversation $conversation, int $userId, bool $archived): void
    {
        $participant = $conversation->participantFor($userId);
        if (!$participant) {
            return;
        }

        $archived ? $participant->archive() : $participant->restoreArchive();
    }

    protected function storeAttachment(Message $message, UploadedFile $file): MessageAttachment
    {
        $path = $file->store('messages', 'public');

        return $message->addAttachment([
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    protected function formatConversation(Conversation $conversation, int $currentUserId): array
    {
        $otherParticipant = $conversation->participants
            ->first(fn (ConversationParticipant $p) => $p->user_id !== $currentUserId);

        $currentParticipant = $conversation->participants
            ->first(fn (ConversationParticipant $p) => $p->user_id === $currentUserId);

        $lastMessage = $conversation->messages->first();

        return [
            'id' => $conversation->id,
            'subject' => $conversation->subject,
            'updated_at' => optional($conversation->updated_at)->toIso8601String(),
            'unread_count' => $conversation->unread_count
                ?? $conversation->messages()
                    ->where('sender_id', '<>', $currentUserId)
                    ->whereNull('read_at')
                    ->count(),
            'is_archived' => (bool) optional($currentParticipant)->archived_at,
            'other_user' => $otherParticipant ? [
                'id' => $otherParticipant->user->id,
                'name' => $otherParticipant->user->nom_complet,
                'avatar' => $otherParticipant->user->photo_profil
                    ? asset($otherParticipant->user->photo_profil)
                    : null,
                'role' => $otherParticipant->role,
            ] : null,
            'last_message' => $lastMessage ? $this->formatMessage($lastMessage, $currentUserId) : null,
        ];
    }

    protected function formatMessage(Message $message, int $currentUserId): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => optional($message->sender)->nom_complet,
            'content' => $message->content,
            'type' => $message->type,
            'is_mine' => $message->sender_id === $currentUserId,
            'read_at' => optional($message->read_at)->toIso8601String(),
            'created_at' => optional($message->created_at)->toIso8601String(),
            'time_for_humans' => optional($message->created_at)->format('d/m/Y H:i'),
            'attachments' => $message->attachments->map(fn (MessageAttachment $attachment) => [
                'id' => $attachment->id,
                'url' => Storage::disk($attachment->disk)->url($attachment->path),
                'name' => $attachment->original_name,
                'mime' => $attachment->mime_type,
                'size' => $attachment->size,
            ])->toArray(),
        ];
    }
}
