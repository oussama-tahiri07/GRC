<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    Route::get('home', [AdminController::class, 'index'])->name('admin-home');

    // Gestion des utilisateurs
    Route::get('gestion_utilisateur', [UserController::class, 'liste_utilisateur'])->name('liste_utilisateur');
    Route::get('create_utilisateur', [UserController::class, 'create_utilisateur'])->name('create_utilisateur');
    Route::post('store_utilisateur', [UserController::class, 'store_utilisateur'])->name('store_utilisateur');
    Route::get('edit_utilisateur/{utilisateur_id}', [UserController::class, 'edit_utilisateur'])->name('edit_utilisateur');
    Route::post('update_utilisateur/{utilisateur_id}', [UserController::class, 'update_utilisateur'])->name('update_utilisateur');
    Route::match(['post', 'delete'], 'delete_utilisateur/{utilisateur_id}', [UserController::class, 'delete_utilisateur'])->name('delete_utilisateur');

    // Gestion des réclamations
    Route::get('liste_reclamations', [AdminController::class, 'getReclamations'])->name('liste_reclamations');
    Route::get('show_reclamation/{reclamation_id}', [AdminController::class, 'show_reclamation'])->name('admin_show_reclamation');
    Route::post('accepte_reclamation/{reclamation_id}', [AdminController::class, 'accepte_reclamation'])->name('accepte_reclamation');
    Route::get('refuse_reclamation/{reclamation_id}', [AdminController::class, 'refuse_reclamation_form'])->name('refuse_reclamation_form');
    Route::post('refuse_reclamation/{reclamation_id}', [AdminController::class, 'refuse_reclamation'])->name('refuse_reclamation');
});

// Authentification & Admin creation (guarded inside controller logic)
Route::get('admin/login', [LoginController::class, 'adminLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'loginAdmin'])->name('admin-login');
Route::post('admin/logout', [LoginController::class, 'logoutAdmin'])->name('admin.logout');

Route::get('admin/register', [RegisterController::class, 'AdminRegisterForm'])->name('admin.register');
Route::post('admin/register', [RegisterController::class, 'createAdmin'])->name('admin-register');
