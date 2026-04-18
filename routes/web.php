<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropiedadesController;
use App\Http\Controllers\AuditoriaController;

use Illuminate\Support\Facades\Route;

// La raíz ahora es el login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Ruta para el dashboard (ajusta si ya tienes una)
    Route::get('/dashboard', [PropiedadesController::class, 'index'])->name('dashboard');
    
    // Rutas para Propiedades (esto crea index, create, store, edit, update, destroy)
    Route::resource('propiedades', PropiedadesController::class);
});

// Rutas para crear propiedades
Route::get('/propiedades/crear', [PropiedadesController::class, 'create'])->name('propiedades.create');
Route::post('/propiedades', [PropiedadesController::class, 'store'])->name('propiedades.store');


Route::middleware(['auth'])->group(function () {
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});