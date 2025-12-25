<?php

namespace App\Http\Controllers\Praticien;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Secretaire;
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
        $contacts = Secretaire::with('user')->get()->map(function (Secretaire $secretaire) {
            $user = $secretaire->user;

            return [
                'user_id' => $user->id,
                'name' => $user->nom_complet,
                'initials' => mb_substr($user->prenom, 0, 1).mb_substr($user->nom, 0, 1),
                'role' => $user->role,
                'role_label' => 'Secrétaire',
            ];
        })->values();

        return view('praticien.chats', [
            'authUser' => $user,
            'initialConversations' => $initialConversations,
            'contacts' => $contacts,
        ]);
    }

    public function conversations()
    {
        return response()->json([
            'conversations' => $this->chatService->listConversationsForUser(Auth::id()),
        ]);
    }

    public function storeConversation(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $targetUser = User::where('id', $data['user_id'])
            ->where('role', 'SECRETAIRE')
            ->firstOrFail();

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
