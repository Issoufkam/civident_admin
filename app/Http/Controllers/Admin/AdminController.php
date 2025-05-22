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

        // Dates
        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        // Agents
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

        // Citoyens
        $citoyensThisMonth = User::where('role', UserRole::CITOYEN)
            ->where('created_at', '>=', $currentMonth)
            ->count();

        $citoyenProgress = $totalCitoyens > 0
            ? round(($citoyensThisMonth / $totalCitoyens) * 100)
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

        // Régions
        $totalRegions = Commune::whereNotNull('region')->distinct('region')->count();

        $activeRegions = Commune::whereNotNull('region')
            ->whereHas('users') // relation users définie sur Commune
            ->distinct('region')
            ->count();

        $regionCoverage = $totalRegions > 0
            ? round(($activeRegions / $totalRegions) * 100)
            : 0;

        // ➤ Performance par région
        $regionsPerformance = Commune::whereNotNull('region')
            ->select('region')
            ->distinct()
            ->get()
            ->map(function ($commune) {
                $regionName = $commune->region;

                $agents = User::where('role', UserRole::AGENT)
                    ->whereHas('commune', function ($q) use ($regionName) {
                        $q->where('region', $regionName);
                    })
                    ->count();

                $demandes = Document::whereHas('user.commune', function ($q) use ($regionName) {
                    $q->where('region', $regionName);
                })->count();

                $rate = $agents > 0 ? round(min(100, ($demandes / $agents))) : 0;

                return [
                    'name' => $regionName,
                    'agents' => $agents,
                    'demandes' => $demandes,
                    'rate' => $rate,
                ];
            });

        //  Derniers agents
        $latestAgents = User::with('commune')
            ->where('role', UserRole::AGENT)
            ->latest()
            ->take(5)
            ->get(['id', 'nom', 'prenom', 'commune_id']); // en tenant compte des colonnes réelles

        // ➤ Cartes dynamiques
        $cards = [
            [
                'label' => 'Agents enregistrés',
                'value' => $totalAgents,
                'trend' => "+$agentProgress% ce mois",
                'color' => 'primary',
            ],
            [
                'label' => 'Citoyens enregistrés',
                'value' => $totalCitoyens,
                'trend' => "+$citoyenProgress% ce mois",
                'color' => 'success',
            ],
            [
                'label' => 'Documents déposés',
                'value' => $totalRequests,
                'trend' => $requestChange >= 0 ? "+$requestChange% ce mois" : "$requestChange% ce mois",
                'color' => 'warning',
            ],
            [
                'label' => 'Régions actives',
                'value' => $activeRegions,
                'trend' => "Couverture à $regionCoverage%",
                'color' => 'secondary',
            ]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'cards',
            'regionsPerformance',
            'latestAgents'
        ));
    }







}
