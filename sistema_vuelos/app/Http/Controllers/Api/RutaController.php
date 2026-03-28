<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ruta;

class RutaController extends Controller
{
    public function index(){
        return response()->json(['status'=>'success','data'=> Ruta::with('vuelos')->get()]);
    }
    public function store(Request $request){
        $datos = $request->validate([
            'origen'         => 'required|string',
            'destino'        => 'required|string',
            'codigo_origen'  => 'required|string|max:5',
            'codigo_destino' => 'required|string|max:5',
            'distancia_km'   => 'nullable|integer',
            'duracion_min'   => 'nullable|integer',
        ]);
        $ruta = Ruta::create($datos);
        return response()->json(['status'=>'success','data'=> $ruta], 201);
    }
    public function show(Ruta $ruta){
        return response()->json(['status'=>'success','data'=> $ruta->load('vuelos')]);
    }
    public function update(Request $request, Ruta $ruta){
        $ruta->update($request->only(['origen','destino','codigo_origen','codigo_destino','distancia_km','duracion_min']));
        return response()->json(['status'=>'success','data'=> $ruta]);
    }
    public function destroy(Ruta $ruta){
        $ruta->delete();
        return response()->json(['status'=>'success','mensaje'=>'Ruta eliminada.']);
    }
}
