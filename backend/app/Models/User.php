<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'date_naissance',
        'sexe',
        'numero_cni',
        'telephone',
        'adresse',
        'quartier',
        'ville',
        'role',
        'statut_compte',
        'photo',
        'photo_profil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
        ];
    }

    // Relations
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function praticien()
    {
        return $this->hasOne(Praticien::class);
    }

    public function secretaire()
    {
        return $this->hasOne(Secretaire::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    // Helpers
    public function isPatient()
    {
        return $this->role === 'PATIENT';
    }

    public function isPraticien()
    {
        return $this->role === 'PRATICIEN';
    }

    public function isSecretaire()
    {
        return $this->role === 'SECRETAIRE';
    }

    public function isAdmin()
    {
        return $this->role === 'ADMIN';
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->name;
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

        public function canAccessPanel(Panel $panel): bool
        {
            return $this->isAdmin();
        }

        public function getFilamentName(): string
        {
            return $this->nom_complet;
        }

        public function getFilamentAvatarUrl(): ?string
        {
            return $this->photo_profil
                ? asset($this->photo_profil)
                : null;
        }
}

