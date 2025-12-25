<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Praticien;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService)
    {
    }

    public function index()
    {
        $user = Auth::user();

        $initialConversations = $this->chatService->listConversationsForUser($user->id);
        
        $praticiens = Praticien::with(['user', 'specialites'])->get();
        \Log::info('ChatController: Found '.count($praticiens).' praticiens');
        
        $contacts = $praticiens->map(function (Praticien $praticien) {
            $primarySpecialty = $praticien->specialites
                ->firstWhere(fn ($specialite) => $specialite->pivot?->est_principale)
                ?? $praticien->specialites->first();

            $contact = [
                'user_id' => $praticien->user->id,
                'name' => 'Dr. '.$praticien->user->nom_complet,
                'initials' => mb_substr($praticien->user->prenom, 0, 1).mb_substr($praticien->user->nom, 0, 1),
                'specialty' => $primarySpecialty?->nom ?? 'Généraliste',
            ];
            
            \Log::info('ChatController: Contact mapped', $contact);
            return $contact;
        })->values();

        \Log::info('ChatController: Total contacts after mapping: '.count($contacts));

        return view('secretaire.messages.index', [
            'authUser' => $user,
            'initialConversations' => $initialConversations,
            'contacts' => $contacts,
        ]);
    }

    public function conversations()
    {
        $userId = Auth::id();

        return response()->json([
            'conversations' => $this->chatService->listConversationsForUser($userId),
        ]);
    }

    public function storeConversation(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $targetUser = User::where('id', $data['user_id'])->where('role', 'PRATICIEN')->firstOrFail();

        $conversation = $this->chatService->getOrCreateOneToOne(Auth::id(), $targetUser->id);

        return response()->json([
            'conversation' => $this->chatService->formatConversation($conversation, Auth::id()),
        ], 201);
    }

    public function show(Conversation $conversation)
    {
        $userId = Auth::id();
        $this->chatService->ensureParticipant($conversation, $userId);

        $messagesPaginator = $this->chatService->conversationMessages($conversation, $userId);
        $this->chatService->markConversationAsRead($conversation, $userId);

        $messages = collect($messagesPaginator->items())->reverse()->values();

        return response()->json([
            'conversation' => $this->chatService->formatConversation($conversation, $userId),
            'messages' => $messages,
            'pagination' => [
                'next_page_url' => $messagesPaginator->nextPageUrl(),
                'prev_page_url' => $messagesPaginator->previousPageUrl(),
                'per_page' => $messagesPaginator->perPage(),
            ],
        ]);
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();
        $this->chatService->ensureParticipant($conversation, $userId);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $message = $this->chatService->sendMessage($conversation, $userId, $data['content']);

        return response()->json([
            'message' => $message,
        ], 201);
    }

    public function markAsRead(Conversation $conversation)
    {
        $userId = Auth::id();
        $this->chatService->ensureParticipant($conversation, $userId);

        $this->chatService->markConversationAsRead($conversation, $userId);

        return response()->json(['status' => 'ok']);
    }
}
