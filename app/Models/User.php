<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable, LogsActivity;

    public const ROLE_CITOYEN = 'citoyen';
    public const ROLE_ADMIN = 'admin'; // Tu peux ajouter d'autres rôles ici

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'role',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function getActivityDescription($event)
    {
        return match($event) {
            'created' => 'Utilisateur créé: '.$this->email,
            'updated' => 'Utilisateur modifié: '.$this->email,
            'deleted' => 'Utilisateur supprimé: '.$this->email,
            default => 'Action inconnue'
        };
    }
}
