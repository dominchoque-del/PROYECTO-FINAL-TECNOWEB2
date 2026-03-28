<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aerolinea;

class AerolineaController extends Controller
{
    public function index()
    {
        $aerolineas = Aerolinea::with('vuelos')->get();
        return view('aerolineas.index', compact('aerolineas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:aerolineas,nombre',
        ]);

        Aerolinea::create($request->only('nombre'));

        return redirect()->route('aerolineas.index')
                         ->with('exito', 'Aerolínea registrada correctamente.');
    }

    public function destroy(Aerolinea $aerolinea)
    {
        $aerolinea->delete();
        return redirect()->route('aerolineas.index')
                         ->with('exito', 'Aerolínea eliminada.');
    }

    // Métodos vacíos requeridos por Route::resource
    public function create() {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
}
