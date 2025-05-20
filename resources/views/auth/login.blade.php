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
     * Cette méthode est appelée manuellement dans authenticated().
     */
    protected function redirectTo()
    {
        if (auth()->user()->role === UserRole::ADMIN) {
            return '/admin/dashboard';
        }

        if (auth()->user()->role === UserRole::AGENT) {
            return '/agent/dashboard';
        }

        // Si rôle citoyen, ou autre
        return '/citoyen/dashboard'; // ou une autre route existante
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
            logger()->warning("Tentative de connexion agent sans commune", ['user' => $user->id]);
        }

        return Route::has('agent.dashboard')
            ? route('agent.dashboard')
            : '/';
    }

    /**
     * Gestion post-authentification
     * Appelée automatiquement par AuthenticatesUsers après login réussi.
     */
    protected function authenticated(Request $request, User $user)
    {
        // Vérification spécifique pour les agents
        if ($user->isAgent() && !$user->commune_id) {
            $this->guard()->logout();
            return redirect()->route('login')
                ->withErrors(['commune' => 'Les agents doivent avoir une commune associée']);
        }

        // Redirection vers l'URL adaptée selon rôle
        return redirect()->intended($this->redirectTo());
    }

    /**
     * La colonne utilisée pour l'authentification (email ici)
     */
    public function username()
    {
        return 'email';
    }
}
