<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
    ];

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->withPivot(['role', 'last_read_at', 'muted_at'])
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('participants', fn ($q) => $q->where('user_id', $userId));
    }

    public function scopeWithParticipantStatus($query, int $userId, ?bool $archived = null)
    {
        return $query->whereHas('participants', function ($q) use ($userId, $archived) {
            $q->where('user_id', $userId)
                ->when(!is_null($archived), fn ($inner) => $archived
                    ? $inner->whereNotNull('archived_at')
                    : $inner->whereNull('archived_at'));
        });
    }

    public function participantFor(int $userId): ?ConversationParticipant
    {
        return $this->participants()->where('user_id', $userId)->first();
    }

    public function otherParticipant(int $userId): ?ConversationParticipant
    {
        return $this->participants()->where('user_id', '<>', $userId)->first();
    }
}
