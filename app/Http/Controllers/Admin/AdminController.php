<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commune;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Gestion des utilisateurs
    public function manageUsers()
    {
        $users = User::with('commune')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $communes = Commune::all();
        return view('admin.users.create', compact('communes'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:citoyen,agent,admin',
            'commune_id' => 'required_if:role,agent|exists:communes,id',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé !');
    }

    public function editUser(User $user)
    {
        $communes = Commune::all();
        return view('admin.users.edit', compact('user', 'communes'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:citoyen,agent,admin',
            'commune_id' => 'required_if:role,agent|exists:communes,id',
        ]);

        $user->update($validated);
        return back()->with('success', 'Profil mis à jour !');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé !');
    }
}
