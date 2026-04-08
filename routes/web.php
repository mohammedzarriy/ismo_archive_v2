<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\SearchController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Import — قبل resource
    Route::get('trainees/import',  [ImportController::class, 'index'])->name('trainees.import');
    Route::post('trainees/import', [ImportController::class, 'store'])->name('trainees.import.store');

    // Trainees
    Route::resource('trainees', TraineeController::class);

    // Documents static routes — كلها قبل resource
    Route::get('documents/bac',              [DocumentController::class, 'index'])->name('documents.bac')->defaults('type', 'Bac');
    Route::get('documents/bac/temp-out',     [DocumentController::class, 'tempOut'])->name('documents.bac.temp-out');
    Route::get('documents/bac/ecoule',       [DocumentController::class, 'ecoule'])->name('documents.bac.ecoule');
    Route::get('documents/bac/final-out',    [DocumentController::class, 'finalOut'])->name('documents.bac.final-out');
    Route::get('documents/diplome',          [DocumentController::class, 'index'])->name('documents.diplome')->defaults('type', 'Diplome');
    Route::get('documents/diplome/prets',    [DocumentController::class, 'prets'])->name('documents.diplome.prets');
    Route::get('documents/bulletin',         [DocumentController::class, 'index'])->name('documents.bulletin')->defaults('type', 'Bulletin');
    Route::get('documents/attestation',      [DocumentController::class, 'index'])->name('documents.attestation')->defaults('type', 'Attestation');

    // Documents resource
    Route::resource('documents', DocumentController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('documents/{document}/sortie', [DocumentController::class, 'sortie'])->name('documents.sortie');
    Route::post('documents/{document}/retour', [DocumentController::class, 'retour'])->name('documents.retour');

    // Movements
    Route::get('movements',       [MovementController::class, 'index'])->name('movements.index');
    Route::get('movements/today', [MovementController::class, 'today'])->name('movements.today');

    // Validations
    Route::get('validations',                              [ValidationController::class, 'index'])->name('validations.index');
    Route::get('trainees/{trainee}/validation/create',     [ValidationController::class, 'create'])->name('validations.create');
    Route::post('trainees/{trainee}/validation',           [ValidationController::class, 'store'])->name('validations.store');
    Route::get('trainees/{trainee}/validation',            [ValidationController::class, 'show'])->name('validations.show');
    Route::delete('validations/{validation}',              [ValidationController::class, 'destroy'])->name('validations.destroy');

    // Users
    Route::resource('users', UserController::class)->except(['show']);

    // Secteurs & Filières
    Route::resource('secteurs', SecteurController::class)->except(['show']);
    Route::resource('filieres', FiliereController::class)->except(['show']);
    Route::get('search', [SearchController::class, 'index'])->name('search');
});

require __DIR__.'/auth.php';