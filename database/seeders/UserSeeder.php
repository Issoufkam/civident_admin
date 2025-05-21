<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un utilisateur administrateur
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Admin',
            'telephone' => '0123456789',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN, 
        ]);

        // Exemple : Création d'un agent
        User::create([
            'nom' => 'Agent',
            'prenom' => 'Jean',
            'telephone' => '0789456123',
            'email' => 'agent@example.com',
            'password' => Hash::make('agent123'),
            'role' => UserRole::AGENT,
        ]);

        // Exemple : Création d'un citoyen
        User::create([
            'nom' => 'Citoyen',
            'prenom' => 'Marie',
            'telephone' => '0598123478',
            'email' => 'citoyen@example.com',
            'password' => Hash::make('citoyen123'),
            'role' => UserRole::CITOYEN,
        ]);
    }
}
