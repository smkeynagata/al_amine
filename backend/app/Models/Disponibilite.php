<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Disponibilite extends Model
{
    use HasFactory;
    protected $fillable = ['praticien_id', 'jour_semaine', 'heure_debut', 'heure_fin', 'est_active'];
    protected $casts = ['est_active' => 'boolean'];
    public function praticien() { return $this->belongsTo(Praticien::class); }
}
