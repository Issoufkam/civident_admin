<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\UserRole;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirige l'utilisateur vers le dashboard approprié selon son rôle.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = auth()->user();
        $role = UserRole::from($user->role); // Convertit la chaîne en enum

        return match($role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::AGENT => redirect()->route('agent.dashboard'),
            default => $this->handleDefaultRedirect($user)
        };
    }

    /**
     * Gère la redirection par défaut pour les autres rôles.
     */
    protected function handleDefaultRedirect($user)
    {
        // Exemple : redirection pour les citoyens ou rôles personnalisés
        // return redirect()->route('citoyen.dashboard');

        // Ou redirection vers une page générique avec warning
        if (app()->environment('local')) {
            logger()->warning("Redirection non configurée pour le rôle", [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
        }

        return redirect()->route('documents.index'); // Fallback vers une route existante
    }
}
