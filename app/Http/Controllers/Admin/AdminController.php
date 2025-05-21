<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commune;
use App\Models\ActivityLog;
use App\Models\Document;
use App\Enums\DocumentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class AdminController extends Controller
{
    // GESTION DES UTILISATEURS
    public function manageUsers()
    {
        $users = User::with('commune')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.dashboard', compact('users'));
    }

    public function createUser()
    {
        $communes = Commune::all();
        $roles = [
            UserRole::ADMIN => 'Administrateur',
            UserRole::AGENT => 'Agent',
            UserRole::CITOYEN => 'Citoyen'
        ];

        return view('admin.users.create', compact('communes', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telephone' => 'nullable|string|max:20',
            'role' => 'required|in:' . implode(',', UserRole::getRoles()),
            'commune_id' => 'required_if:role,' . UserRole::AGENT . '|exists:communes,id',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    public function editUser(User $user)
    {
        $communes = Commune::all();
        $roles = [
            UserRole::AGENT => 'Agent',
            UserRole::ADMIN => 'Administrateur'
        ];

        return view('admin.users.edit', compact('user', 'communes', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'role' => 'required|in:' . implode(',', UserRole::getRoles()),
            'commune_id' => 'nullable|required_if:role,' . UserRole::AGENT . '|exists:communes,id'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour avec succès !');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé définitivement !');
    }

    // STATISTIQUES GLOBALES UTILISATEURS + DOCUMENTS
    public function showStatistics()
    {
        // Utilisateurs
        $totalUsers = User::count();
        $totalAdmins = User::where('role', UserRole::ADMIN)->count();
        $totalAgents = User::where('role', UserRole::AGENT)->count();
        $totalCitoyens = User::where('role', UserRole::CITOYEN)->count();

        // Documents
        $approuvee = DocumentStatus::APPROUVEE->value;
        $rejetee = DocumentStatus::REJETEE->value;

        $documentStats = Document::select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = '$approuvee' THEN 1 ELSE 0 END) as approuvees"),
                DB::raw("SUM(CASE WHEN status = '$rejetee' THEN 1 ELSE 0 END) as rejetees"),
                'type'
            )
            ->groupBy('type')
            ->get();

        return view('admin.statistics', compact(
            'totalUsers',
            'totalAdmins',
            'totalAgents',
            'totalCitoyens',
            'documentStats'
        ));
    }

    // HISTORIQUE DES ACTIONS
    public function viewHistory()
    {
        $activities = ActivityLog::with('causer')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.history', compact('activities'));
    }


   public function dashboard()
{
    // Utilisateurs
    $totalUsers = User::count();
    $totalAdmins = User::where('role', UserRole::ADMIN)->count();
    $totalAgents = User::where('role', UserRole::AGENT)->count();
    $totalCitoyens = User::where('role', UserRole::CITOYEN)->count();

    // Statistiques temporelles - mois courant vs mois précédent
    $currentMonth = now()->startOfMonth();
    $previousMonth = now()->subMonth()->startOfMonth();

    $agentsThisMonth = User::where('role', UserRole::AGENT)
        ->where('created_at', '>=', $currentMonth)
        ->count();

    $agentsLastMonth = User::where('role', UserRole::AGENT)
        ->whereBetween('created_at', [$previousMonth, $currentMonth])
        ->count();

    $agentChange = $agentsLastMonth > 0
        ? round((($agentsThisMonth - $agentsLastMonth) / $agentsLastMonth) * 100)
        : ($agentsThisMonth > 0 ? 100 : 0);

    $agentProgress = $totalAgents > 0
        ? round(($agentsThisMonth / $totalAgents) * 100)
        : 0;

    // Documents
    $totalRequests = Document::count();

    $requestsThisMonth = Document::where('created_at', '>=', $currentMonth)->count();
    $requestsLastMonth = Document::whereBetween('created_at', [$previousMonth, $currentMonth])->count();

    $requestChange = $requestsLastMonth > 0
        ? round((($requestsThisMonth - $requestsLastMonth) / $requestsLastMonth) * 100)
        : ($requestsThisMonth > 0 ? 100 : 0);

    $requestProgress = $totalRequests > 0
        ? round(($requestsThisMonth / $totalRequests) * 100)
        : 0;

    // Couverture régionale
    $activeRegions = Commune::whereHas('users')->distinct('region')->count();
    $totalRegions = Commune::distinct('region')->count();

    $regionCoverage = $totalRegions > 0
        ? round(($activeRegions / $totalRegions) * 100)
        : 0;

    $stats = [
        'total_agents' => $totalAgents,
        'agent_progress' => $agentProgress,
        'agent_change' => $agentChange,

        'total_requests' => $totalRequests,
        'request_progress' => $requestProgress,
        'request_change' => $requestChange,

        'active_regions' => $activeRegions,
        'region_coverage' => $regionCoverage,
    ];

    return view('admin.dashboard', compact(
        'totalUsers',
        'totalAdmins',
        'totalAgents',
        'totalCitoyens',
        'stats'
    ));
}



}
