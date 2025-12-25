<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DemandeRdv extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'praticien_id',
        'specialite_id',
        'date_heure_souhaitee',
        'motif',
        'statut',
        'traite_par',
        'reponse_secretaire',
        'mode_paiement',
        'methode_paiement_choisie',
        'paiement_effectue',
        'paydunya_token',
        'montant_avance',
        'payment_amount',
        'payment_method',
    ];
    protected $casts = [
        'date_heure_souhaitee' => 'datetime',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function praticien()
    {
        return $this->belongsTo(Praticien::class);
    }
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
    public function traitePar()
    {
        return $this->belongsTo(Secretaire::class, 'traite_par');
    }
    public function rendezVous()
    {
        return $this->hasOne(RendezVous::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'demande_rdv_id');
    }
}
