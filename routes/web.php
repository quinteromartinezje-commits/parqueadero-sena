<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarifaController;

/**
 * Rutas de la aplicación Parking Como en Casa
 * 
 * Aquí se definen todas las rutas web del sistema.
 * La ruta raíz redirige al módulo de tarifas.
 */

// Ruta raíz: redirige a la página de tarifas
Route::get('/', function () {
    return redirect('/tarifas');
});

// Rutas del módulo de Tarifas (CRUD)
Route::get('/tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
Route::post('/tarifas', [TarifaController::class, 'store'])->name('tarifas.store');
Route::put('/tarifas/{id}', [TarifaController::class, 'update'])->name('tarifas.update');
Route::delete('/tarifas/{id}', [TarifaController::class, 'destroy'])->name('tarifas.destroy');