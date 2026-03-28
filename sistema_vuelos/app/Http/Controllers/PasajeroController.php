<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasajero;
use App\Models\Vuelo;

class PasajeroController extends Controller
{
    public function index()
    {
        $pasajeros = Pasajero::with('vuelo')->paginate(15);
        $vuelos    = Vuelo::all();
        return view('pasajeros.index', compact('pasajeros', 'vuelos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'vuelo_id'        => 'required|exists:vuelos,id',
        ]);

        Pasajero::create($request->only('nombre_completo', 'vuelo_id'));

        return redirect()->route('pasajeros.index')
                         ->with('exito', 'Pasajero registrado correctamente.');
    }

    public function destroy(Pasajero $pasajero)
    {
        $pasajero->delete();
        return redirect()->route('pasajeros.index')
                         ->with('exito', 'Pasajero eliminado.');
    }

    // Métodos vacíos requeridos por Route::resource
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}
