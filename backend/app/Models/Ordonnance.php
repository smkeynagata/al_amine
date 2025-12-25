<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Ordonnance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'consultation_id',
        'praticien_id',
        'patient_id',
        'date_ordonnance',
        'medicaments',
        'conseils',
        'rdv_suivi'
    ];
    
    protected $casts = [
        'medicaments' => 'array',
        'date_ordonnance' => 'date',
        'rdv_suivi' => 'date'
    ];
    
    public function consultation() { 
        return $this->belongsTo(Consultation::class); 
    }
    
    public function praticien() { 
        return $this->belongsTo(Praticien::class); 
    }
    
    public function patient() { 
        return $this->belongsTo(Patient::class); 
    }
}
