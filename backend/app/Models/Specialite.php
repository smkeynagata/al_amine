<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'tarif_base',
        'icone',
    ];

    protected $casts = [
        'tarif_base' => 'decimal:2',
    ];

    public function praticiens()
    {
        return $this->belongsToMany(Praticien::class, 'praticien_specialite')
            ->withPivot('niveau_expertise', 'est_principale')
            ->withTimestamps();
    }

    public function demandeRdvs()
    {
        return $this->hasMany(DemandeRdv::class);
    }

    public function getTarifFormatAttribute()
    {
        return number_format($this->tarif_base, 0, ',', ' ') . ' FCFA';
    }
}

