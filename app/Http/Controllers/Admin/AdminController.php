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
}
