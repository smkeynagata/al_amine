<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'patient_id',
        'rendez_vous_id',
        'facture_id',
        'secretaire_id',
        'channel',
        'status',
        'sent_at',
        'error',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(ReminderTemplate::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class, 'rendez_vous_id');
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function secretaire()
    {
        return $this->belongsTo(Secretaire::class);
    }
}
