<?php

use App\Http\Controllers\PrestataireController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Prestataire Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'prestataire', 'middleware' => 'auth:prestataire'], function () {

    Route::get('home', [PrestataireController::class, 'index'])->name('prestataire_home');
    Route::get('inbox', [PrestataireController::class, 'inbox'])->name('prestataire_inbox');
    Route::get('reclamation_detail/{reclamation_id}', [PrestataireController::class, 'get_reclamation_details'])->name('reclamation_details');
    Route::get('solution/{reclamation_id}', [PrestataireController::class, 'resoudre_reclamation'])->name('prestataire_solution');
    Route::post('reclamation-traiter/{reclamation_id}', [PrestataireController::class, 'reclamation_resolue'])->name('reclamation_resolue');
});

Route::get('prestataire/login', [LoginController::class, 'prestataireLoginForm'])->name('prestataire.login');
Route::post('prestataire/login', [LoginController::class, 'loginPrestataire'])->name('prestataire-login');
Route::post('prestataire/logout', [LoginController::class, 'logoutPrestataire'])->name('prestataire.logout');

// Registration of prestataires (guarded inside controller so only admins can create them)
Route::get('prestataire/register', [RegisterController::class, 'PrestataireRegisterForm'])->name('prestataire.register');
Route::post('prestataire/register', [RegisterController::class, 'createPrestataire'])->name('prestataire-register');
