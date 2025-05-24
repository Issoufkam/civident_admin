<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Middleware pour s'assurer que seul un admin peut enregistrer un utilisateur
        $this->middleware('can:create,user');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:25'],
            'prenom' => ['required', 'string', 'max:50'],
            'telephone' => ['required', 'string', 'max:15', 'unique:users','min:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'commune_id' => ['nullable', 'exists:communes,id'],
            'role' => ['required', 'string', 'in:citoyen,agent,admin'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:7048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('profiles', 'public');
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
            'adresse' => $request->adresse,
            'commune_id' => $request->commune_id,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }
}
