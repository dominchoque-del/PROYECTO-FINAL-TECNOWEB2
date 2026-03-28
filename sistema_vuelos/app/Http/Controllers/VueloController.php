<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vuelo;
use App\Models\Aerolinea;

class VueloController extends Controller
{
    // READ: Listar todos los vuelos (con Eager Loading para evitar N+1)
    public function index()
    {
        //  Ahora lee de la BD real, con paginación y carga ansiosa
        $vuelos = Vuelo::with('aerolinea')->paginate(10);
        return view('vuelos.index', compact('vuelos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $aerolineas = Aerolinea::all();
        return view('vuelos.create', compact('aerolineas'));
    }

    // CREATE: Guardar nuevo vuelo
    public function store(Request $request)
    {
        $request->validate([
            'aerolinea_id' => 'required|exists:aerolineas,id',
            'destino'      => 'required|string|max:255',
        ]);

        Vuelo::create($request->only('aerolinea_id', 'destino'));

        return redirect()->route('vuelos.index')
                         ->with('exito', 'Vuelo programado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Vuelo $vuelo)
    {
        $aerolineas = Aerolinea::all();
        return view('vuelos.edit', compact('vuelo', 'aerolineas'));
    }

    // UPDATE: Modificar vuelo
    public function update(Request $request, Vuelo $vuelo)
    {
        $request->validate([
            'destino' => 'required|string|max:255',
        ]);

        $vuelo->update($request->only('destino', 'aerolinea_id'));

        return redirect()->route('vuelos.index')
                         ->with('exito', 'Vuelo actualizado correctamente.');
    }

    // DELETE (Soft Delete): Cancelar vuelo sin borrar de la BD
    public function destroy(Vuelo $vuelo)
    {
        $vuelo->delete();
        return back()->with('info', 'Vuelo cancelado (guardado en papelera).');
    }

    // RESTAURAR: Recuperar un vuelo cancelado
    public function restaurar($id)
    {
        $vuelo = Vuelo::withTrashed()->findOrFail($id);
        $vuelo->restore();
        return back()->with('exito', 'Vuelo restaurado correctamente.');
    }
}
