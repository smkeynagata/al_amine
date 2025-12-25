<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Paiement extends Model
{
    use HasFactory;
    protected $fillable = ['facture_id', 'patient_id', 'demande_rdv_id', 'montant', 'methode_paiement', 'date_paiement', 'reference', 'numero_transaction', 'effectue_par', 'statut'];
    protected $casts = ['date_paiement' => 'datetime', 'montant' => 'decimal:2'];
    public function facture() { return $this->belongsTo(Facture::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function demandeRdv() { return $this->belongsTo(DemandeRdv::class); }
    public function effectuePar() { return $this->belongsTo(Secretaire::class, 'effectue_par'); }
}
