<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'numero_securite_sociale',
        'allergies',
        'antecedents',
        'mutuelle',
        'numero_mutuelle',
        'poids',
        'taille',
        'groupe_sanguin',
        'maladies_chroniques',
        'medicaments_actuels',
        'chirurgies_passees',
        'personne_contact_urgence',
        'fumeur',
        'consommation_alcool',
        'notes_supplementaires',
    ];

    protected $casts = [
        'fumeur' => 'boolean',
        'consommation_alcool' => 'boolean',
        'poids' => 'decimal:2',
        'taille' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function demandeRdvs()
    {
        return $this->hasMany(DemandeRdv::class);
    }
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }

    public function examens()
    {
        return $this->hasMany(Examen::class);
    }

    public function documentsMedicaux()
    {
        return $this->hasMany(DocumentMedical::class);
    }
}
