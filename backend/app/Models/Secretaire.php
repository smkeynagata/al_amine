<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Secretaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'matricule',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted(): void
    {
        static::creating(function (Secretaire $secretaire): void {
            if (blank($secretaire->matricule)) {
                $secretaire->matricule = 'SEC-' . strtoupper(uniqid());
            }
        });
    }
    public function demandesTraitees()
    {
        return $this->hasMany(DemandeRdv::class, 'traite_par');
    }
    public function rendezVousValides()
    {
        return $this->hasMany(RendezVous::class, 'valide_par');
    }
    public function paiementsEffectues()
    {
        return $this->hasMany(Paiement::class, 'effectue_par');
    }
}
