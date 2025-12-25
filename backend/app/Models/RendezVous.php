<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;
    protected $table = 'rendez_vous';
    protected $fillable = [
        'patient_id',
        'praticien_id',
        'demande_rdv_id',
        'date_heure_rdv',
        'duree',
        'statut',
        'valide_par',
        'notes',
    ];
    protected $casts = [
        'date_heure_rdv' => 'datetime',
    ];

    protected array $statutLabels = [
        'PLANIFIE' => 'Planifié',
        'CONFIRME' => 'Confirmé',
        'EN_COURS' => 'En cours',
        'TERMINE' => 'Terminé',
        'ANNULE' => 'Annulé',
        'ABSENT' => 'Patient absent',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function praticien()
    {
        return $this->belongsTo(Praticien::class);
    }
    public function demandeRdv()
    {
        return $this->belongsTo(DemandeRdv::class);
    }
    public function validePar()
    {
        return $this->belongsTo(Secretaire::class, 'valide_par');
    }
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function getDateHeureAttribute(): ?Carbon
    {
        return $this->date_heure_rdv;
    }

    public function getDureeMinutesAttribute(): int
    {
        return (int) ($this->duree ?? 0);
    }

    public function getStatutDisplayAttribute(): string
    {
        $statut = strtoupper((string) $this->statut);

        return $this->statutLabels[$statut] ?? ucfirst(strtolower($statut));
    }
}
