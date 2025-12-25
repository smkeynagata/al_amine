<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'label',
        'channel_email',
        'channel_sms',
        'email_subject',
        'email_body',
        'sms_body',
    ];

    protected $casts = [
        'channel_email' => 'boolean',
        'channel_sms' => 'boolean',
    ];

    public const TYPE_FACTURE_IMPAYEE = 'FACTURE_IMPAYEE';
    public const TYPE_RDV_CONFIRMATION = 'RDV_CONFIRMATION';

    public function logs()
    {
        return $this->hasMany(ReminderLog::class, 'template_id');
    }
}
