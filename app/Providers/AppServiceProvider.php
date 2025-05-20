<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Enums\DocumentType;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Redirection personnalisée selon le rôle
        Redirect::macro('toRoleDashboard', function () {
            if (auth()->check()) {
                /** @var User $user */
                $user = auth()->user();
                return redirect()->to($user->roleDashboard());
            }

            return redirect('/');
        });

        // Règle de validation personnalisée pour les métadonnées de documents
        Validator::extend('document_metadata', function ($attribute, $value, $parameters, $validator) {
            $type = $validator->getData()['type'] ?? null;

            return match ($type) {
                DocumentType::NAISSANCE->value => isset($value['nom_enfant'], $value['prenom_enfant']),
                DocumentType::MARIAGE->value => isset($value['epoux'], $value['epouse']),
                // Ajoute d'autres types de documents ici
                default => false,
            };
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
