<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        // Citoyen: seulement ses propres documents
        if ($user->isCitoyen()) {
            return $user->id === $document->user_id;
        }

        // Agent: seulement sa commune
        if ($user->isAgent()) {
            return $user->commune_id === $document->commune_id;
        }

        // Admin: accès complet
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCitoyen() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        return $user->isAdmin() || 
              ($user->isAgent() && $user->commune_id === $document->commune_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        return $user->isAdmin();
    }
}