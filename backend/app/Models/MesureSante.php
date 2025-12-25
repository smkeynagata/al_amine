<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesureSante extends Model
{
    protected $table = 'mesures_sante';
    
    protected $fillable = [
        'patient_id',
        'date_mesure',
        'poids',
        'taille',
        'tension_systolique',
        'tension_diastolique',
        'frequence_cardiaque',
        'temperature',
        'glycemie',
        'notes',
    ];

    protected $casts = [
        'date_mesure' => 'date',
        'poids' => 'decimal:2',
        'taille' => 'decimal:2',
        'temperature' => 'decimal:1',
        'glycemie' => 'decimal:2',
    ];

    // Relations
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Accesseur pour IMC
    public function getImcAttribute()
    {
        if ($this->poids && $this->taille) {
            $tailleM = $this->taille / 100; // Convertir cm en m
            return round($this->poids / ($tailleM * $tailleM), 1);
        }
        return null;
    }
}
