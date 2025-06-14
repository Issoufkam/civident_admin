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
        return $this->role === UserRole::ADMIN->value;
    }

    public function isAgent(): bool
    {
        return $this->role === UserRole::AGENT->value;
    }

    public function isCitoyen(): bool
    {
        return $this->role === UserRole::CITOYEN->value;
    }

    // Relation avec commune (si nécessaire)
    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
    public function processedDocuments()
    {
        return $this->hasMany(Document::class, 'agent_id');
    }

    
    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
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
        return match($this->role) { // $this->role est une string, donc comparez-la aux valeurs string de l'enum
            UserRole::ADMIN->value => route('admin.dashboard'),
            UserRole::AGENT->value => route('agent.dashboard'),
            UserRole::CITOYEN->value => route('citoyen.dashboard'),
            default => throw new \Exception('Rôle utilisateur non reconnu')
        };
    }
}
