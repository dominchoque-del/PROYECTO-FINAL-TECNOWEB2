<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aerolinea;
use App\Http\Resources\AerolineaResource;

class AerolineaApiController extends Controller
{
    public function index(){
        return AerolineaResource::collection(Aerolinea::with('vuelos')->get());
    }
    public function show(Aerolinea $aerolinea){
        return new AerolineaResource($aerolinea->load('vuelos'));
    }
    public function store(Request $request){
        $datos = $request->validate([
            'nombre' => 'required|string|max:255|unique:aerolineas,nombre',
            'codigo' => 'nullable|string|max:10',
            'pais'   => 'nullable|string|max:100',
        ]);
        $a = Aerolinea::create($datos);
        return response()->json(['status'=>'success','mensaje'=>'Aerolínea registrada.','data'=>new AerolineaResource($a)],201);
    }
    public function update(Request $request, Aerolinea $aerolinea){
        $datos = $request->validate([
            'nombre' => 'sometimes|string|max:255|unique:aerolineas,nombre,'.$aerolinea->id,
            'codigo' => 'sometimes|nullable|string|max:10',
            'pais'   => 'sometimes|nullable|string|max:100',
        ]);
        $aerolinea->update($datos);
        return response()->json(['status'=>'success','data'=>new AerolineaResource($aerolinea)]);
    }
    public function destroy(Aerolinea $aerolinea){
        $aerolinea->delete();
        return response()->json(['status'=>'success','mensaje'=>'Aerolínea eliminada.']);
    }
}
