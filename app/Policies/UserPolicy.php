<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des utilisateurs.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut voir un utilisateur spécifique.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->id === $model->id;
    }

    /**
     * Détermine si l'utilisateur peut créer un nouvel utilisateur (agent).
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un utilisateur.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer un utilisateur.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut restaurer un utilisateur.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement un utilisateur.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
