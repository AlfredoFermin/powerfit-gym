<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TipoMembresiaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\RolRequerido;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Acceso público: inicio de sesión
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Zona privada
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/panel', DashboardController::class)->name('dashboard');

    // Solo administrador
    Route::middleware(RolRequerido::class . ':Administrador')->group(function () {
        Route::resource('usuarios', UsuarioController::class)->except('show')->parameters(['usuarios' => 'id']);
        Route::resource('tipos', TipoMembresiaController::class)->except('show')->parameters(['tipos' => 'id']);
    });

    // Administrador y recepcionista
    Route::middleware(RolRequerido::class . ':Administrador,Recepcionista')->group(function () {
        Route::resource('membresias', MembresiaController::class)->except('show')->parameters(['membresias' => 'id']);
        Route::resource('pagos', PagoController::class)->except('show')->parameters(['pagos' => 'id']);
        Route::resource('asistencias', AsistenciaController::class)->except('show')->parameters(['asistencias' => 'id']);
    });

    // Administrador, recepcionista y entrenador
    Route::middleware(RolRequerido::class . ':Administrador,Recepcionista,Entrenador')->group(function () {
        Route::resource('clases', ClaseController::class)->except('show')->parameters(['clases' => 'id']);
    });
});
