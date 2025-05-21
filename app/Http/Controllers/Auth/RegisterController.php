<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
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

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Gérer le téléchargement de la photo
        $photoPath = null;

        if (request()->hasFile('photo')) {
            $photo = request()->file('photo');
            $photoPath = $photo->store('profiles', 'public');
        }

        return User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'photo' => $photoPath,
            'adresse' => $data['adresse'] ?? null,
            'commune_id' => $data['commune_id'] ?? null,
            'role' => $data['role'],
        ]);
    }
}
