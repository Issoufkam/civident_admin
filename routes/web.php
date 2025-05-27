<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CommuneAdminController;
use App\Http\Controllers\Admin\DocumentAdminController;
use App\Http\Controllers\Admin\DepartementAdminController;
use App\Models\Commune;

Auth::routes();

// Routes réservées aux administrateurs
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Tableau de bord admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs (agents)
    Route::prefix('agents')->name('agents.')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('index');
        Route::get('create', [AgentController::class, 'create'])->name('create');
        Route::post('/', [AgentController::class, 'store'])->name('store');
        Route::get('{id}', [AgentController::class, 'show'])->name('show');
        Route::get('{id}/edit', [AgentController::class, 'edit'])->name('edit');
        Route::put('{id}', [AgentController::class, 'update'])->name('update');
        Route::delete('{agent}', [AgentController::class, 'destroy'])->name('destroy');
    });

    // Statistiques globales
    Route::get('/statistics', [AdminController::class, 'showStatistics'])->name('statistics');

    // Paramètres
    Route::get('/Settings', [AdminController::class, 'showSettings'])->name('settings');

    // Recherche
    Route::get('/Search', [AdminController::class, 'showSearch'])->name('search');

    // Notifications
    Route::get('/Notifications', [AdminController::class, 'showNotifications'])->name('notifications');

    // Sidebar
    Route::get('/ToggleSidebar', [AdminController::class, 'showToggleSidebar'])->name('togglesidebar');

    // Historique des actions
    Route::get('/history', [AdminController::class, 'viewHistory'])->name('history');

    // Gestion des communes
    Route::prefix('communes')->group(function () {
        Route::get('/', [CommuneAdminController::class, 'index'])->name('communes.index');
        Route::get('/create', [CommuneAdminController::class, 'create'])->name('communes.create');
        Route::post('/', [CommuneAdminController::class, 'store'])->name('communes.store');
        Route::get('/{commune}/edit', [CommuneAdminController::class, 'edit'])->name('communes.edit');
        Route::put('/{commune}', [CommuneAdminController::class, 'update'])->name('communes.update');
        Route::delete('/{commune}', [CommuneAdminController::class, 'destroy'])->name('communes.destroy');
    });

    // // Gestion des départements
    // Route::prefix('departements')->group(function () {
    //     Route::get('/', [DepartementAdminController::class, 'index'])->name('departements.index');
    //     Route::get('/create', [DepartementAdminController::class, 'create'])->name('departements.create');
    //     Route::post('/', [DepartementAdminController::class, 'store'])->name('departements.store');
    //     Route::get('/{departement}/edit', [DepartementAdminController::class, 'edit'])->name('departements.edit');
    //     Route::put('/{departement}', [DepartementAdminController::class, 'update'])->name('departements.update');
    //     Route::delete('/{departement}', [DepartementAdminController::class, 'destroy'])->name('departements.destroy');
    // });

    // Gestion des régions
    Route::prefix('regions')->group(function () {
        Route::get('/', [CommuneAdminController::class, 'regionsIndex'])->name('regions.index');
        Route::get('/create', [CommuneAdminController::class, 'createRegion'])->name('regions.create');
        Route::post('/', [CommuneAdminController::class, 'regionStore'])->name('regions.store');
        Route::get('/{region}/edit', [CommuneAdminController::class, 'regionEdit'])->name('regions.edit');
        Route::put('/', [CommuneAdminController::class, 'regionUpdate'])->name('regions.update');
        Route::delete('/regions', [CommuneAdminController::class, 'regionDestroy'])->name('regions.destroy');
    });
});

// Routes pour les agents
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentAdminController::class, 'index'])->name('index');
        Route::get('/create', [DocumentAdminController::class, 'create'])->name('create');
        Route::post('/', [DocumentAdminController::class, 'store'])->name('store');
        Route::get('/{document}', [DocumentAdminController::class, 'showDocument'])->name('show');
        Route::post('/{document}/approve', [DocumentAdminController::class, 'approve'])->name('approve');
        Route::post('/{document}/reject', [DocumentAdminController::class, 'rejectDocument'])->name('reject');
        Route::get('/{document}/edit', [DocumentAdminController::class, 'editDoc'])->name('edit');
        Route::put('/{document}', [DocumentAdminController::class, 'update'])->name('update');
        Route::get('/{document}/pdf', [DocumentAdminController::class, 'generateDocumentPdf'])->name('pdf');
        Route::get('/{document}/duplicate', [DocumentAdminController::class, 'generateDuplicata'])->name('duplicata');
        Route::get('/documents/{attachment}/download', [DocumentAdminController::class, 'download'])->name('documents.download');
        Route::post('/documents/{id}/duplicata', [DocumentAdminController::class, 'createDuplicata'])->name('agent.documents.create.duplicata');
    });
});

// Déconnexion personnalisée
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Authentification
Route::post('login', [LoginController::class, 'login'])->name('login');

// Autres routes publiques/commentées
// Route::middleware(['auth', 'role:citoyen'])->group(function () {
//     Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
// });
