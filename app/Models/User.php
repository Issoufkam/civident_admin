<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use Notifiable, LogsActivity;

    // public const ROLE_CITOYEN = 'citoyen';
    // public const ROLE_AGENT = 'agent';
    // public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'role',
        'commune_id'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    // app/Models/User.php
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
