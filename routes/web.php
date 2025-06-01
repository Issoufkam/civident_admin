<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CommuneAdminController;
use App\Http\Controllers\Admin\DocumentAdminController;
use App\Http\Controllers\Citizen\CitizenController; // Importation du contrôleur Citoyen
use App\Http\Controllers\Citizen\DocumentCitizenController; // Importation du contrôleur de documents pour Citoyen
// App\Http\Controllers\Admin\DepartementAdminController; // Non utilisé dans le code fourni, commenté
use App\Models\Commune;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirection de la racine vers la page de login
Route::get('/', function () {
    return redirect()->route('login');
});

// IMPORTANT : Désactive la création de la route /home par Auth::routes()
Auth::routes(['home' => false]);

// Route pour le traitement du login (Auth::routes() le gère déjà, mais explicite pour clarté si 'home' est false)
Route::post('login', [LoginController::class, 'login'])->name('login');

// --- Routes réservées aux administrateurs ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Tableau de bord admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs (agents)
    Route::prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::get('create', [AgentController::class, 'create'])->name('create');
        Route::post('/', [AgentController::class, 'store'])->name('store');
        Route::get('{agent}', [AgentController::class, 'show'])->name('show'); // Afficher les détails d'un agent
        Route::get('{agent}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('{agent}', [AgentController::class, 'update'])->name('update');
        Route::delete('{agent}', [AgentController::class, 'destroy'])->name('destroy');
    });

    // Statistiques globales (pour les admins)
    Route::get('/statistics', [AdminController::class, 'showStatistics'])->name('statistics');

    // Paramètres (placeholders, à implémenter dans AdminController)
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('settings');
    Route::get('/search', [AdminController::class, 'showSearch'])->name('search');
    Route::get('/notifications', [AdminController::class, 'showNotifications'])->name('notifications');
    // Route::get('/toggle-sidebar', [AdminController::class, 'showToggleSidebar'])->name('togglesidebar'); // Généralement géré en JS côté client

    // Historique des actions
    Route::get('/history', [AdminController::class, 'viewHistory'])->name('history');

    // Gestion des communes
    Route::prefix('communes')->name('communes.')->group(function () { // Ajout du name() au groupe
        Route::get('/', [CommuneAdminController::class, 'index'])->name('index');
        Route::get('/create', [CommuneAdminController::class, 'create'])->name('create');
        Route::post('/', [CommuneAdminController::class, 'store'])->name('store');
        Route::get('/{commune}/edit', [CommuneAdminController::class, 'edit'])->name('edit');
        Route::put('/{commune}', [CommuneAdminController::class, 'update'])->name('update');
        Route::delete('/{commune}', [CommuneAdminController::class, 'destroy'])->name('destroy');
    });

    // Gestion des régions
    Route::prefix('regions')->name('regions.')->group(function () { // Ajout du name() au groupe
        Route::get('/', [CommuneAdminController::class, 'regionsIndex'])->name('index');
        Route::get('/create', [CommuneAdminController::class, 'createRegion'])->name('create');
        Route::post('/', [CommuneAdminController::class, 'regionStore'])->name('store');
        // Correction des routes PUT et DELETE pour utiliser un paramètre {region}
        Route::get('/{region}/edit', [CommuneAdminController::class, 'regionEdit'])->name('edit');
        Route::put('/{region}', [CommuneAdminController::class, 'regionUpdate'])->name('update'); // {region} doit être l'identifiant unique de la région
        Route::delete('/{region}', [CommuneAdminController::class, 'regionDestroy'])->name('destroy'); // {region} doit être l'identifiant unique de la région
    });

    // Performances (Assumons que c'est une vue gérée par AdminController)
    // Si 'admin.lieux.index' est la route pour les performances
    Route::get('/performances', [AdminController::class, 'showPerformances'])->name('performances');
    // Ou si c'est lié à un contrôleur spécifique pour les lieux :
    // Route::get('/lieux', [DepartementAdminController::class, 'index'])->name('lieux.index');
});

// --- Routes pour les agents municipaux ---
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

    // Gestion des documents par les agents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentAdminController::class, 'index'])->name('index');
        Route::get('/approuve', [DocumentAdminController::class, 'approuve'])->name('approuve');
        Route::get('/attente', [DocumentAdminController::class, 'attente'])->name('attente');
        Route::get('/rejete', [DocumentAdminController::class, 'rejete'])->name('rejete');
        Route::get('/create', [DocumentAdminController::class, 'create'])->name('create');
        Route::post('/', [DocumentAdminController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentAdminController::class, 'showDocument'])->name('show');
        Route::get('/{document}/download', [DocumentAdminController::class, 'download'])->name('download');
        Route::post('/{document}/approve', [DocumentAdminController::class, 'approve'])->name('approve');
        Route::post('/{document}/reject', [DocumentAdminController::class, 'rejectDocument'])->name('reject');
        Route::get('/{document}/edit', [DocumentAdminController::class, 'editDoc'])->name('edit');
        Route::put('/{document}', [DocumentAdminController::class, 'update'])->name('update');
        Route::get('/{document}/pdf', [DocumentAdminController::class, 'generateDocumentPdf'])->name('pdf');
        Route::get('/{document}/duplicate', [DocumentAdminController::class, 'generateDuplicata'])->name('duplicata');
        // Correction du nom de la route et du paramètre pour le téléchargement des pièces jointes
        Route::get('/attachments/{attachment}/download', [DocumentAdminController::class, 'downloadAttachment'])->name('download.attachment');
        // Simplification du nom de la route pour la création de duplicata
        Route::post('/{document}/create-duplicata', [DocumentAdminController::class, 'createDuplicata'])->name('create.duplicata');

        // Routes pour l'impression des certificats (simplification de l'URI)
        Route::get('/certificats/naissance/{document}', [DocumentAdminController::class, 'printNaissance'])->name('certificats.naissance');
        Route::get('/certificats/mariage/{document}', [DocumentAdminController::class, 'printMariage'])->name('certificats.mariage');
        Route::get('/certificats/deces/{document}', [DocumentAdminController::class, 'printDeces'])->name('certificats.deces');
    });
});

// // --- Routes pour les citoyens ---
// Route::middleware(['auth', 'role:citoyen'])->prefix('citizen')->name('citizen.')->group(function () {
//     // Tableau de bord citoyen
//     Route::get('/dashboard', [CitizenController::class, 'dashboard'])->name('dashboard');

//     // Demandes d'actes d'état civil en ligne
//     Route::prefix('demandes')->name('demandes.')->group(function () {
//         Route::get('/', [DocumentCitizenController::class, 'index'])->name('index'); // Lister les demandes du citoyen
//         Route::get('/create/{type?}', [DocumentCitizenController::class, 'create'])->name('create'); // Formulaire de nouvelle demande (type d'acte optionnel)
//         Route::post('/', [DocumentCitizenController::class, 'store'])->name('store'); // Soumettre la demande
//         Route::get('/{document}', [DocumentCitizenController::class, 'show'])->name('show'); // Voir les détails d'une demande
//         Route::get('/{document}/edit', [DocumentCitizenController::class, 'edit'])->name('edit'); // Modifier une demande (si statut le permet)
//         Route::put('/{document}', [DocumentCitizenController::class, 'update'])->name('update'); // Mettre à jour une demande
//         Route::delete('/{document}', [DocumentCitizenController::class, 'destroy'])->name('destroy'); // Annuler/Supprimer une demande

//         // Paiement des frais de timbre en ligne
//         Route::get('/{document}/pay', [DocumentCitizenController::class, 'showPaymentForm'])->name('pay');
//         Route::post('/{document}/process-payment', [DocumentCitizenController::class, 'processPayment'])->name('process-payment');

//         // Accès aux fichiers PDF signés numériquement
//         Route::get('/{document}/download-pdf', [DocumentCitizenController::class, 'downloadSignedPdf'])->name('download-pdf');
//     });

//     // Accès à des statistiques publiques (si applicable)
//     Route::get('/statistics', [CitizenController::class, 'showPublicStatistics'])->name('statistics');
// });

// --- Déconnexion personnalisée ---
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// --- Route Fallback (à placer en dernier) ---
// Gère les routes non trouvées et redirige l'utilisateur en fonction de son statut d'authentification et de son rôle.
Route::fallback(function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Rediriger vers le tableau de bord par défaut de l'utilisateur authentifié
    if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->isAgent()) {
        return redirect()->route('agent.dashboard');
    } elseif (Auth::user()->isCitoyen()) {
        return redirect()->route('citizen.dashboard');
    }

    // Fallback si le rôle n'est pas reconnu ou si l'utilisateur est connecté mais sans rôle défini
    return redirect()->route('login');
});
