<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VueloApiController;
use App\Http\Controllers\Api\AerolineaApiController;
use App\Http\Controllers\Api\PasajeroApiController;
use App\Http\Controllers\Api\NaveController;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\MonitoreoController;

// ── AUTH (sin prefijo adicional) ──────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('registro',          [AuthController::class, 'registro']);
    Route::post('login',             [AuthController::class, 'login']);
    Route::get('verificar/{token}',  [AuthController::class, 'verificar']);
    Route::post('reset-solicitud',   [AuthController::class, 'resetSolicitud']);
    Route::post('reset-password',    [AuthController::class, 'resetPassword']);
});

// ── MÓDULOS PRINCIPALES ───────────────────────────────────
Route::apiResource('vuelos',     VueloApiController::class);
Route::apiResource('aerolineas', AerolineaApiController::class);
Route::apiResource('pasajeros',  PasajeroApiController::class);
Route::apiResource('naves',      NaveController::class);
Route::apiResource('rutas',      RutaController::class);
Route::apiResource('reservas',   ReservaController::class)->only(['index','store','show','destroy','update']);

// ── MONITOREO ─────────────────────────────────────────────
Route::get('monitoreo',                    [MonitoreoController::class, 'index']);
Route::post('monitoreo',                   [MonitoreoController::class, 'store']);
Route::get('monitoreo/{vuelo_id}/historial',[MonitoreoController::class, 'historial']);

// ── USUARIOS ───────────────────────────────────────────────
Route::get('usuarios', function () {
    return response()->json([
        'status' => 'success',
        'data' => \App\Models\Usuario::select('id', 'nombre', 'email', 'rol')->get()
    ]);
});

Route::post('usuarios', function (\Illuminate\Http\Request $request) {
    $datos = $request->validate([
        'nombre' => 'required|string|max:100',
        'email' => 'required|email|unique:usuarios,email',
        'password' => 'required|string|min:6',
        'rol' => 'sometimes|in:admin,operador,cliente',
    ]);
    
    $usuario = \App\Models\Usuario::create([
        'nombre' => $datos['nombre'],
        'email' => $datos['email'],
        'password' => $datos['password'],
        'rol' => $datos['rol'] ?? 'cliente',
        'email_verificado' => true,
    ]);
    
    return response()->json(['status' => 'success', 'mensaje' => 'Usuario creado.', 'data' => $usuario], 201);
});

Route::put('usuarios/{id}', function (\Illuminate\Http\Request $request, $id) {
    $usuario = \App\Models\Usuario::findOrFail($id);
    
    $datos = $request->validate([
        'nombre' => 'sometimes|string|max:100',
        'password' => 'sometimes|string|min:6',
        'rol' => 'sometimes|in:admin,operador,cliente',
    ]);
    
    if (isset($datos['password'])) {
        $datos['password'] = \Illuminate\Support\Facades\Hash::make($datos['password']);
    }
    
    $usuario->update($datos);
    
    return response()->json(['status' => 'success', 'mensaje' => 'Usuario actualizado.', 'data' => $usuario]);
});

Route::delete('usuarios/{id}', function ($id) {
    $usuario = \App\Models\Usuario::findOrFail($id);
    $usuario->delete();
    return response()->json(['status' => 'success', 'mensaje' => 'Usuario eliminado.']);
});

// ── RESTAURAR VUELO (Soft Delete) ────────────────────────
Route::get('vuelos/{id}/restaurar', [VueloApiController::class, 'restaurar']);

// ── DASHBOARD STATS ───────────────────────────────────────
Route::get('stats', function () {
    return response()->json([
        'status' => 'success',
        'data'   => [
            'total_vuelos'      => \App\Models\Vuelo::count(),
            'vuelos_activos'    => \App\Models\Vuelo::where('estado', 'programado')->count(),
            'total_pasajeros'   => \App\Models\Pasajero::count(),
            'total_reservas'    => \App\Models\Reserva::count(),
            'total_aerolineas'  => \App\Models\Aerolinea::count(),
            'total_naves'       => \App\Models\Nave::count(),
            'vuelos_cancelados' => \App\Models\Vuelo::onlyTrashed()->count(),
            'ingresos_total'    => \App\Models\Reserva::where('estado','confirmada')->sum('total'),
        ]
    ]);
});
