<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CommuneAdminController;
use App\Http\Controllers\Admin\DocumentAdminController;
use App\Http\Controllers\CitoyenController; // Importation du contrôleur Citoyen
// App\Http\Controllers\Citizen\DocumentCitizenController; // Commenté car CitoyenController semble gérer les documents aussi
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

    // ROUTES POUR LA CONFIGURATION DES PRIX
    // Correction ici: Appelle showSettings() au lieu de settingsForm()
    Route::get('/settings', [AdminController::class, 'showSettings'])->name('settings'); // Affiche le formulaire
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save'); // Gère la soumission du formulaire

    Route::get('/search', [AdminController::class, 'showSearch'])->name('search');
    Route::get('/notifications', [AdminController::class, 'showNotifications'])->name('notifications');

    // Historique des actions
    Route::get('/history', [AdminController::class, 'viewHistory'])->name('history');

    // Gestion des communes
    Route::prefix('communes')->name('communes.')->group(function () {
        Route::get('/', [CommuneAdminController::class, 'index'])->name('index');
        Route::get('/create', [CommuneAdminController::class, 'create'])->name('create');
        Route::post('/', [CommuneAdminController::class, 'store'])->name('store');
        Route::get('/{commune}/edit', [CommuneAdminController::class, 'edit'])->name('edit');
        Route::put('/{commune}', [CommuneAdminController::class, 'update'])->name('update');
        Route::delete('/{commune}', [CommuneAdminController::class, 'destroy'])->name('destroy');
    });

    // Gestion des régions
    Route::prefix('regions')->name('regions.')->group(function () {
        Route::get('/', [CommuneAdminController::class, 'regionsIndex'])->name('index');
        Route::get('/create', [CommuneAdminController::class, 'createRegion'])->name('create');
        Route::post('/', [CommuneAdminController::class, 'regionStore'])->name('store');
        Route::get('/{region}/edit', [CommuneAdminController::class, 'regionEdit'])->name('edit');
        Route::put('/{region}', [CommuneAdminController::class, 'regionUpdate'])->name('update');
        Route::delete('/{region}', [CommuneAdminController::class, 'regionDestroy'])->name('destroy');
    });

    // Performances (Assumons que c'est une vue gérée par AdminController)
    Route::get('/performances', [AdminController::class, 'showPerformances'])->name('performances');
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
        Route::get('/settings', [DocumentAdminController::class, 'settings'])->name('settings'); // Si les agents ont aussi leurs propres settings

        // Simplification du nom de la route pour la création de duplicata
        Route::post('/{document}/create-duplicata', [DocumentAdminController::class, 'createDuplicata'])->name('create.duplicata');

        // Routes pour l'impression des certificats (simplification de l'URI)
        Route::get('/certificats/naissance/{document}', [DocumentAdminController::class, 'printNaissance'])->name('certificats.naissance');
        Route::get('/certificats/mariage/{document}', [DocumentAdminController::class, 'printMariage'])->name('certificats.mariage');
        Route::get('/certificats/deces/{document}', [DocumentAdminController::class, 'printDeces'])->name('certificats.deces');
    });
});

// --- Routes pour les citoyens (désactivées dans votre code actuel) ---
Route::middleware(['auth', 'role:citoyen'])->prefix('citoyen')->name('citoyen.')->group(function () {
    // Tableau de bord citoyen
    Route::get('/dashboard', [CitoyenController::class, 'dashboard'])->name('dashboard');

    // Demandes d'actes d'état civil en ligne
    Route::prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', [CitoyenController::class, 'index'])->name('index'); // Lister les demandes du citoyen
        Route::get('/create', [CitoyenController::class, 'create'])->name('create'); // Formulaire de nouvelle demande
        Route::post('/', [CitoyenController::class, 'store'])->name('store'); // Soumettre la demande
        Route::get('/{document}', [CitoyenController::class, 'show'])->name('show'); // Voir les détails d'une demande
        Route::get('/{document}/edit', [CitoyenController::class, 'edit'])->name('edit'); // Modifier une demande (si statut le permet)
        Route::put('/{document}', [CitoyenController::class, 'update'])->name('update'); // Mettre à jour une demande
        Route::delete('/{document}', [CitoyenController::class, 'destroy'])->name('destroy'); // Annuler/Supprimer une demande

        // Routes pour les formulaires spécifiques
        Route::get('/naissance', [CitoyenController::class, 'formNaissance'])->name('naissance');
        Route::get('/mariage', [CitoyenController::class, 'formMariage'])->name('mariage');
        Route::get('/deces', [CitoyenController::class, 'formDeces'])->name('deces');
        Route::get('/certificat-vie', [CitoyenController::class, 'formVie'])->name('certificat-vie');
        Route::get('/certificat-entretien', [CitoyenController::class, 'formEntretien'])->name('certificat-entretien');
        Route::get('/certificat-revenu', [CitoyenController::class, 'formRevenu'])->name('certificat-revenu');
        Route::get('/certificat-divorce', [CitoyenController::class, 'formDivorce'])->name('certificat-divorce');


        // Paiement des frais de timbre en ligne
        Route::get('/{document}/pay', [CitoyenController::class, 'showPaymentForm'])->name('pay');
        Route::post('/{document}/process-payment', [CitoyenController::class, 'processPayment'])->name('process-payment');
        Route::get('/{document}/payment-confirmation', [CitoyenController::class, 'showPaymentConfirmation'])->name('paiements.confirmation');

        // Téléchargement d'un document (après paiement réussi)
        Route::get('/{document}/download', [CitoyenController::class, 'download'])->name('download'); // Utilise la méthode download mise à jour

        // Demande de duplicata
        Route::post('/request-duplicata', [CitoyenController::class, 'requestDuplicata'])->name('request-duplicata'); // Utilise la méthode requestDuplicata


    });

    // Accès à des statistiques publiques (si applicable)
    Route::get('/statistics', [CitoyenController::class, 'showPublicStatistics'])->name('statistics');
});


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
        return redirect()->route('citoyen.dashboard'); // Corrigé le nom de la route
    }

    // Fallback si le rôle n'est pas reconnu ou si l'utilisateur est connecté mais sans rôle défini
    return redirect()->route('login');
});
