<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Praticien;
use App\Services\PatientChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    public function __construct(private PatientChatService $chatService)
    {
        $this->middleware(['auth', 'role:PATIENT']);
    }

    public function index(): Response
    {
        $user = auth()->user();
        $patient = $user->patient;

        $practitioners = collect();

        if ($patient) {
            $practitioners = Praticien::query()
                ->whereHas('rendezVous', fn ($q) => $q->where('patient_id', $patient->id))
                ->with(['user', 'specialites'])
                ->get()
                ->unique('user_id')
                ->values()
                ->map(function (Praticien $praticien) {
                    $specialites = $praticien->specialites?->pluck('nom')->filter()->join(', ');

                    return [
                        'id' => $praticien->user->id,
                        'name' => 'Dr. '.$praticien->user->nom_complet,
                        'specialites' => $specialites,
                    ];
                });
        }

        return response()->view('patient.messagerie.index', [
            'practitioners' => $practitioners,
        ]);
    }

    public function conversations(Request $request): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $filters = $request->validate([
            'archived' => ['nullable', 'boolean'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $conversations = $this->chatService->listConversations($userId, $filters);

        return response()->json($conversations);
    }

    public function storeConversation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'practitioner_id' => ['required', 'exists:users,id'],
        ]);

        $userId = (int) $request->user()->id;

        $conversation = $this->chatService->ensureConversationWithPractitioner(
            $userId,
            (int) $data['practitioner_id']
        );

        return response()->json(
            $this->chatService->presentConversation($conversation, $userId),
            201
        );
    }

    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $conversation = $this->chatService->getConversationForUser($conversation->id, $userId);

        $messages = $this->chatService->conversationMessages(
            $conversation,
            $userId,
            perPage: (int) $request->integer('per_page', 30)
        );

        return response()->json($messages);
    }

    public function storeMessage(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        $conversation = $this->chatService->getConversationForUser($conversation->id, $user->id);

        $validated = $request->validate([
            'content' => ['nullable', 'string'],
            'files.*' => ['nullable', 'file', 'max:10240'],
        ]);

        if (empty($validated['content']) && !$request->hasFile('files')) {
            throw ValidationException::withMessages([
                'content' => 'Veuillez saisir un message ou joindre un fichier.',
            ]);
        }

        $files = collect($request->file('files', []))
            ->filter()
            ->all();

        $payload = $this->chatService->sendMessage(
            $conversation,
            $user->id,
            (string) ($validated['content'] ?? ''),
            $files
        );

        return response()->json($payload, 201);
    }

    public function markAsRead(Request $request, Conversation $conversation): JsonResponse
    {
        $userId = (int) $request->user()->id;
        $conversation = $this->chatService->getConversationForUser($conversation->id, $userId);

        $this->chatService->markConversationAsRead($conversation, $userId);

        return response()->json(['status' => 'ok']);
    }

    public function archive(Request $request, Conversation $conversation): JsonResponse
    {
        $data = $request->validate([
            'archived' => ['required', 'boolean'],
        ]);

        $userId = (int) $request->user()->id;
        $conversation = $this->chatService->getConversationForUser($conversation->id, $userId);

        $this->chatService->setArchive($conversation, $userId, (bool) $data['archived']);

        return response()->json(['status' => 'ok']);
    }
}
