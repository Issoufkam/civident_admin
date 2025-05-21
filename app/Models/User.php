<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use Notifiable, LogsActivity;

    const ROLE_AGENT = 'agent';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'photo',
        'adresse',
        'commune_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Accessor pour l’URL de la photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-profile.png');
    }

    // Méthodes de vérification de rôle
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isAgent(): bool
    {
        return $this->role === UserRole::AGENT;
    }

    public function isCitoyen(): bool
    {
        return $this->role === UserRole::CITOYEN;
    }

    // Relation avec commune (si nécessaire)
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function getActivityDescription(string $event): string
    {
        return match($event) {
            'created' => "Utilisateur créé: {$this->email}",
            'updated' => "Utilisateur modifié: {$this->email}",
            'deleted' => "Utilisateur supprimé: {$this->email}",
            default => "Action inconnue sur l'utilisateur"
        };
    }

    public static function getRoles()
    {
        return [
            UserRole::CITOYEN,
            UserRole::AGENT,
            UserRole::ADMIN
        ];
    }

    public function roleDashboard()
    {
        return match($this->role) {
            UserRole::ADMIN => route('admin.dashboard'),
            UserRole::AGENT => route('agent.dashboard'),
            UserRole::CITOYEN => route('citoyen.dashboard'),
            default => throw new \Exception('Rôle utilisateur non reconnu')
        };
    }
}
