<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\UserRole;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirection après connexion selon le rôle utilisateur.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        return match ($user->role) {
            UserRole::ADMIN => $this->adminRedirect(),
            UserRole::AGENT => $this->agentRedirect($user),
            default => '/',
        };
    }

    /**
     * URL de redirection pour un admin
     */
    private function adminRedirect(): string
    {
        return Route::has('admin.dashboard')
            ? route('admin.dashboard')
            : '/';
    }

    /**
     * URL de redirection pour un agent
     */
    private function agentRedirect(User $user): string
    {
        if (!$user->commune_id) {
            logger()->warning("Tentative de connexion agent sans commune", [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        return Route::has('agent.dashboard')
            ? route('agent.dashboard')
            : '/';
    }

    /**
     * Gestion post-authentification
     */
    protected function authenticated(Request $request, $user)
    {
        // Régénération de la session pour sécurité
        $request->session()->regenerate();

        // Journalisation de la connexion
        logger()->info('Utilisateur connecté', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'date' => now()->toDateTimeString(),
        ]);

        return match ($user->role) {
            UserRole::ADMIN => redirect()->route('admin.dashboard'),
            UserRole::AGENT => redirect()->route('agent.dashboard'),
            default => redirect('/'),
        };
    }

    /**
     * La colonne utilisée pour l'authentification
     */
    public function username(): string
    {
        return 'email';
    }
}
