<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Praticien extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'service_id',
    // 'numero_ordre' supprimé
        'tarif_consultation',
        'annees_experience',
        'biographie',
    ];
    protected $casts = [
        'tarif_consultation' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
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
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }
    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }

    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'praticien_specialite')
            ->withPivot('niveau_expertise', 'est_principale')
            ->withTimestamps();
    }

    public function getTarifFormatAttribute()
    {
        return number_format($this->tarif_consultation, 0, ',', ' ') . ' FCFA';
    }
}
