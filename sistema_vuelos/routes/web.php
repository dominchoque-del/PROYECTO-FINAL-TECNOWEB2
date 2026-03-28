<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VueloController;
use App\Http\Controllers\AerolineaController;
use App\Http\Controllers\PasajeroController;

Route::get('/', fn() => file_get_contents(public_path('index.html')));

// Verificación de email desde enlace del correo
Route::get('/verificar/{token}', function ($token) {
    $u = \App\Models\Usuario::where('token_verificacion', $token)->first();
    if (!$u) return view('auth.verificacion', ['ok' => false]);
    $u->update(['email_verificado' => true, 'token_verificacion' => null]);
    return view('auth.verificacion', ['ok' => true, 'nombre' => $u->nombre]);
});

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset_form', ['token' => $token]);
});

Route::get('/vuelos/{id}/restaurar', [VueloController::class, 'restaurar'])->name('vuelos.restaurar');
Route::resource('vuelos',     VueloController::class);
Route::resource('aerolineas', AerolineaController::class);
Route::resource('pasajeros',  PasajeroController::class);
