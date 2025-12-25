<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'demande_rdv_id',
        'patient_id',
        'numero_facture',
        'date_facture',
        'montant_total',
        'montant_paye',
        'montant_restant',
        'statut',
        'lignes',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'montant_total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'lignes' => 'array',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function demandeRdv()
    {
        return $this->belongsTo(DemandeRdv::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
