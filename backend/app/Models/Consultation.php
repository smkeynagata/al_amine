<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Consultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'rendez_vous_id', 'praticien_id', 'patient_id', 'date_consultation',
        'motif', 'constantes', 'examen_clinique', 'diagnostic', 'notes',
        'est_validee', 'validee_le',
    ];
    protected $casts = [
        'date_consultation' => 'datetime',
        'constantes' => 'array',
        'est_validee' => 'boolean',
        'validee_le' => 'datetime',
    ];
    public function rendezVous() { return $this->belongsTo(RendezVous::class); }
    public function praticien() { return $this->belongsTo(Praticien::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function ordonnance() { return $this->hasOne(Ordonnance::class); }
    public function ordonnances() { return $this->hasMany(Ordonnance::class); }
    public function facture() { return $this->hasOne(Facture::class); }
    public function examens() { return $this->hasMany(Examen::class); }
    public function documentsMedicaux() { return $this->hasMany(DocumentMedical::class); }
}
