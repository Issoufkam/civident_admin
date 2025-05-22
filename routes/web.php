<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\CommuneAdminController;

use App\Http\Controllers\DocumentController;
use App\Models\Commune;

Auth::routes();

// // Redirection racine vers la page de login
// Route::get('/', function () {
//     return redirect()->route('login');
// });

// Routes réservées aux administrateurs
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Tableau de bord admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs (agents)
    Route::prefix('agents')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('agents.index');
        Route::get('/create', [AgentController::class, 'create'])->name('agents.create');
        Route::post('/', [AgentController::class, 'store'])->name('agents.store');
        Route::get('/{user}/edit', [AgentController::class, 'edit'])->name('agents.edit');
        Route::put('/{user}', [AgentController::class, 'update'])->name('agents.update');
        Route::delete('/{user}', [AgentController::class, 'destroy'])->name('agents.delete');
    });

    // Statistiques globales
    Route::get('/statistics', [AdminController::class, 'showStatistics'])->name('statistics');

    // Historique des actions
    Route::get('/history', [AdminController::class, 'viewHistory'])->name('history');

    // Gestion des communes
    Route::prefix('communes')->group(function () {
        Route::get('/', [CommuneAdminController::class, 'index'])->name('communes.index');
        Route::get('/create', [CommuneAdminController::class, 'create'])->name('communes.create');
        Route::post('/', [CommuneAdminController::class, 'store'])->name('communes.store');
        Route::get('/{commune}/edit', [CommuneAdminController::class, 'edit'])->name('communes.edit');
        Route::put('/{commune}', [CommuneAdminController::class, 'update'])->name('communes.update');
        Route::delete('/{commune}', [CommuneAdminController::class, 'destroy'])->name('communes.delete');
    });

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


// Routes réservées aux agents
Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {

    // Tableau de bord agent
    Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');

    // Gestion des agents
    Route::prefix('agents')->group(function () {
        Route::get('/', [AgentController::class, 'index'])->name('agents.index');
        Route::get('/create', [AgentController::class, 'create'])->name('agents.create');
        Route::post('/', [AgentController::class, 'store'])->name('agents.store');
        Route::delete('/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');
    });

    // Gestion des documents
    Route::prefix('documents')->group(function () {
        Route::get('/', [AgentController::class, 'documents'])->name('documents.index');
        Route::get('/{document}', [AgentController::class, 'showDocument'])->name('documents.show');
        Route::post('/{document}/approve', [AgentController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/{document}/reject', [AgentController::class, 'rejectDocument'])->name('documents.reject');
        Route::get('/{document}/pdf', [AgentController::class, 'generateDocumentPdf'])->name('documents.pdf');
    });
});


// Déconnexion personnalisée
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::post('login', [LoginController::class, 'login'])->name('login');


// Routes publiques ou pour autres rôles (commentées pour l'instant)
// Route::middleware(['auth', 'role:citoyen'])->group(function () {
//     Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
// });
