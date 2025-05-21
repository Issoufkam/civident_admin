<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Constructeur : middleware 'guest' sauf pour logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Champ utilisé pour l'authentification.
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Redirige l'utilisateur connecté selon son rôle.
     */
    protected function authenticated(Request $request, $user)
    {
        // Régénération de session par sécurité
        $request->session()->regenerate();

        // Agent sans commune => déconnexion immédiate
        if ($user->role === UserRole::AGENT && !$user->commune_id) {
            $this->guard()->logout();

            Log::warning('Tentative de connexion d\'un agent sans commune.', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'ip'      => $request->ip(),
            ]);

            return redirect()->route('login')
                ->withErrors(['commune' => 'Les agents doivent avoir une commune associée.']);
        }

        // Redirection selon rôle
        try {
            return $this->redirectBasedOnRole($user);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la redirection après connexion', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return redirect('/')
                ->with('error', 'Une erreur est survenue lors de la redirection.');
        }
    }

    /**
     * Redirection en fonction du rôle de l'utilisateur.
     */
    protected function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            UserRole::ADMIN   => $this->redirectToAdminDashboard(),
            UserRole::AGENT   => $this->redirectToAgentDashboard(),
            UserRole::CITOYEN => $this->redirectToCitizenDashboard(),
            default           => $this->redirectToFallback($user),
        };
    }

    /**
     * Redirection tableau de bord admin.
     */
    protected function redirectToAdminDashboard()
    {
        if (Route::has('admin.dashboard')) {
            return redirect()->route('admin.dashboard');
        }

        Log::error('Route "admin.dashboard" introuvable.');
        return $this->redirectToFallback();
    }

    /**
     * Redirection tableau de bord agent.
     */
    protected function redirectToAgentDashboard()
    {
        if (Route::has('agent.dashboard')) {
            return redirect()->route('agent.dashboard');
        }

        Log::error('Route "agent.dashboard" introuvable.');
        return $this->redirectToFallback();
    }

    /**
     * Redirection espace citoyen.
     */
    protected function redirectToCitizenDashboard()
    {
        if (Route::has('documents.index')) {
            return redirect()->route('documents.index');
        }

        Log::error('Route "documents.index" introuvable.');
        return $this->redirectToFallback();
    }

    /**
     * Fallback si aucune route ne correspond.
     */
    protected function redirectToFallback(User $user = null)
    {
        if ($user) {
            Log::warning('Redirection par défaut utilisée pour un rôle inconnu.', [
                'user_id' => $user->id,
                'role'    => $user->role,
            ]);
        }

        return redirect('/')
            ->with('warning', 'Redirection par défaut appliquée.');
    }

    /**
     * Déconnexion personnalisée.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::channel('auth')->info('Déconnexion utilisateur', [
                'user_id' => Auth::id(),
                'email'   => Auth::user()->email,
                'ip'      => $request->ip(),
                'time'    => now()->toDateTimeString()
            ]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
