<!-- <?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Redirections principales
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login')->name('login.redirect');

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/

Auth::routes(['register' => true, 'reset' => false]); // Ajustez selon vos besoins

/*
|--------------------------------------------------------------------------
| Routes protégées par authentification
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Espace Administrateur
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
    ->name('admin.')
    ->group(function() {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    });


    /*
    |--------------------------------------------------------------------------
    | Espace Agent
    |--------------------------------------------------------------------------
    */
Route::prefix('agent')
    ->middleware('role:agent')
    ->name('agent.')
    ->group(function () {
        Route::get('/dashboard', [AgentController::class, 'dashboard'])->name('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | Route de fallback pour les utilisateurs authentifiés
    |--------------------------------------------------------------------------
    */
    Route::fallback(function () {
        return redirect()->route('login');
    })->name('fallback');

});

/*
|--------------------------------------------------------------------------
| Routes publiques (hors authentification)
|--------------------------------------------------------------------------
*/

// Ajoutez ici vos routes publiques si nécessaire


// // routes/web.php
// Route::middleware(['auth'])->group(function () {
//     // Accessible à tous les utilisateurs authentifiés
//     Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
//     Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
//     Route::get('/documents/{document}/download', [DocumentController::class, 'downloadJustificatif'])->name('documents.download');

//     // Réservé aux citoyens
//     Route::middleware(['can:create,App\Models\Document'])->group(function () {
//         Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
//         Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
//     });

//     // Réservé aux agents/admin
//     Route::middleware(['can:update,App\Models\Document'])->group(function () {
//         Route::put('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('documents.update-status');
//     });
// }); -->
