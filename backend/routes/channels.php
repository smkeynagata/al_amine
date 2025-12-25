<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::channel('conversations.{conversationId}', function ($user, int $conversationId) {
    return $user && \App\Models\Conversation::query()
        ->where('id', $conversationId)
        ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
        ->exists();
});
