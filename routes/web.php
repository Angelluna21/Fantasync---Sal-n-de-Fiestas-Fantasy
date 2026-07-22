<?php

use App\Http\Controllers\CategoriaPlatilloController;
use App\Http\Controllers\ContratoBuilderController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PlatilloController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ServicioGastronomicoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\NominaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

// Rutas para invitados (Login)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('auth/google', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Rutas protegidas (Requieren sesión iniciada)
Route::middleware('auth')->group(function () {
    // Dashboard general
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('logs/logins', [\App\Http\Controllers\LoginLogController::class, 'index'])->name('logs.logins');

    // Recursos generales
    Route::resource('sucursales', SucursalController::class)->parameters(['sucursales' => 'sucursal']);
    Route::resource('salones', SalonController::class)->parameters(['salones' => 'salon']);
    Route::resource('categorias', CategoriaPlatilloController::class);
    Route::resource('platillos', PlatilloController::class);
    Route::resource('ingredientes', IngredienteController::class);
    Route::resource('eventos', EventoController::class);
    Route::resource('servicios-gastronomicos', ServicioGastronomicoController::class);

    Route::get('nominas/reporte-pdf', [NominaController::class, 'reportePdf'])->name('nominas.reporte-pdf');
    Route::resource('nominas', NominaController::class);


    // Test de Livewire para Platillos
    Route::get('/test-platillos', function () {
        return \Livewire\Livewire::mount('platillo-manager');
    });

    // Rutas de Reportes y Contratos (Protegidas dentro del login)
    Route::get('contratos/crear', [ContratoBuilderController::class, 'create'])->name('contratos.crear');
    Route::post('contratos/crear', [ContratoBuilderController::class, 'store'])->name('contratos.crear.store');
    Route::post('/contratos/guardar', [ContratoBuilderController::class, 'store'])->name('contratos.store');
    Route::get('contratos', [ContratoController::class, 'index'])->name('contratos.index');
    Route::get('contratos/{contrato}/editar', [ContratoBuilderController::class, 'edit'])->name('contratos.edit');
    Route::get('contratos/{contrato}', [ContratoController::class, 'show'])->name('contratos.show');
    Route::delete('contratos/{contrato}', [ContratoController::class, 'destroy'])->name('contratos.destroy');
    Route::post('/insumos/store-ajax', [PlatilloController::class, 'storeAjax'])->name('insumos.storeAjax');

    Route::get('reportes/insumos/{id}', [ReporteController::class, 'insumosEvento'])->name('reportes.insumos');
    Route::get('reportes/comanda/{contrato}', [\App\Http\Controllers\ComandaController::class, 'showByContrato'])->name('reportes.comanda');
    Route::get('reportes/comanda-rapida', [ReporteController::class, 'comandaRapida'])->name('reportes.comanda-rapida');
    Route::get('eventos/{evento}/menu', [EventoController::class, 'menuConfig'])->name('eventos.menu');

    // Rutas protegidas por superadmin
    Route::middleware('superadmin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('logs/actividad', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.activity');
    });
});
