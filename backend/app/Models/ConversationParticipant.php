<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'last_read_at',
        'muted_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'muted_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update(['last_read_at' => now()]);
    }

    public function mute(): void
    {
        $this->update(['muted_at' => now()]);
    }

    public function unmute(): void
    {
        $this->update(['muted_at' => null]);
    }

    public function archive(): void
    {
        $this->update(['archived_at' => now()]);
    }

    public function restoreArchive(): void
    {
        $this->update(['archived_at' => null]);
    }
}
