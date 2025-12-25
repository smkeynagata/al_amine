<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentMedical extends Model
{
    use HasFactory;

    protected $table = 'documents_medicaux';

    protected $fillable = [
        'patient_id',
        'consultation_id',
        'praticien_id',
        'type_document',
        'titre',
        'description',
        'fichier',
        'date_document',
        'delivre_par',
    ];

    protected $casts = [
        'date_document' => 'date',
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
