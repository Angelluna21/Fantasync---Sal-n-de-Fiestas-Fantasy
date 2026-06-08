<?php

use App\Http\Controllers\CategoriaPlatilloController;
use App\Http\Controllers\ContratoBuilderController;
use App\Http\Controllers\ContratoPreviewController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PlatilloController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ServicioGastronomicoController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('auth/google', [\App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('auth/google/callback', [\App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::get('contratos/crear', [ContratoBuilderController::class, 'create'])->name('contratos.crear');
Route::post('contratos/crear', [ContratoBuilderController::class, 'store'])->name('contratos.crear.store');
Route::get('contrato-demo', [ContratoPreviewController::class, 'show'])->name('contrato.demo');
Route::get('reportes/insumos/{id}', [ReporteController::class, 'insumosEvento'])->name('reportes.insumos');
Route::get('reportes/comanda/{contrato}', [\App\Http\Controllers\ComandaController::class, 'showByContrato'])->name('reportes.comanda');
Route::post('/contratos/guardar', [ContratoBuilderController::class, 'store'])->name('contratos.store');
Route::get('eventos/{evento}/menu', [EventoController::class, 'menuConfig'])->name('eventos.menu');
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('logs/logins', [\App\Http\Controllers\LoginLogController::class, 'index'])->name('logs.logins');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::resource('sucursales', SucursalController::class)->parameters([
        'sucursales' => 'sucursal'
    ]);
    Route::resource('salones', SalonController::class)->parameters([
        'salones' => 'salon'
    ]);
    Route::resource('categoria-platillos', CategoriaPlatilloController::class);
    Route::resource('platillos', PlatilloController::class);
    Route::resource('ingredientes', IngredienteController::class);
    Route::resource('eventos', EventoController::class);
    Route::resource('servicios-gastronomicos', ServicioGastronomicoController::class);
    Route::get('/test-platillos', function () {
    return \Livewire\Livewire::mount('platillo-manager');
});
});