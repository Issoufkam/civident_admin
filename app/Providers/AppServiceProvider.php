<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

use App\Models\User;
use App\Enums\DocumentType;
use App\View\Composers\SidebarComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Tu peux y enregistrer des services si besoin
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerRedirectMacros();
        $this->registerCustomValidators();
        $this->registerViewComposers();
    }

    /**
     * Redirections personnalisées basées sur le rôle utilisateur.
     */
    protected function registerRedirectMacros(): void
    {
        Redirect::macro('toRoleDashboard', function () {
            if (auth()->check()) {
                /** @var User $user */
                $user = auth()->user();
                return redirect()->to($user->roleDashboard());
            }

            return redirect('/');
        });
    }

    /**
     * Définir les règles de validation personnalisées.
     */
    protected function registerCustomValidators(): void
    {
        Validator::extend('document_metadata', function ($attribute, $value, $parameters, $validator) {
            $type = $validator->getData()['type'] ?? null;

            return match ($type) {
                DocumentType::NAISSANCE->value => isset($value['nom_enfant'], $value['prenom_enfant']),
                DocumentType::MARIAGE->value   => isset($value['epoux'], $value['epouse']),
                // Ajoute d'autres types ici si nécessaire
                default => false,
            };
        });
    }

    /**
     * Injecter des variables dynamiques dans certaines vues.
     */
    protected function registerViewComposers(): void
    {
        View::composer('partials.sidebar', SidebarComposer::class);
    }
}
