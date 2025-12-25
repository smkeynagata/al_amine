<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'praticien_id',
        'type_examen',
        'titre',
        'description',
        'date_examen',
        'laboratoire',
        'resultats',
        'fichier_resultat',
        'statut',
        'commentaire_medecin',
    ];

    protected $casts = [
        'date_examen' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function praticien()
    {
        return $this->belongsTo(Praticien::class);
    }
}
