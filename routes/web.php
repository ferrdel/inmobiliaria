<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropiedadesController;
use App\Http\Controllers\AuditoriaController;

use Illuminate\Support\Facades\Route;

// La raiz es el login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Ruta para el dashboard 
    Route::get('/dashboard', [PropiedadesController::class, 'index'])->name('dashboard');
    
    // Rutas para Propiedades (index, create, store, edit, update, destroy)
    Route::resource('propiedades', PropiedadesController::class)->parameters([
    'propiedades' => 'propiedad' 
]);
});

// Rutas para crear propiedades
Route::get('/propiedades/crear', [PropiedadesController::class, 'create'])->name('propiedades.create');
Route::post('/propiedades', [PropiedadesController::class, 'store'])->name('propiedades.store');

// Ruta para mostrar el formulario con los datos de una propiedad a editar
Route::get('/propiedades/{propiedad}/editar', [PropiedadesController::class, 'edit'])->name('propiedades.edit');

// Ruta para procesar los cambios (usamos PUT o PATCH)
Route::put('/propiedades/{propiedad}', [PropiedadesController::class, 'update'])->name('propiedades.update');

// Ruta para eliminar una propiedad 
Route::delete('/propiedades/{propiedad}', [PropiedadesController::class, 'destroy'])->name('propiedades.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});