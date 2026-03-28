<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nave;
use App\Http\Resources\NaveResource;

class NaveController extends Controller
{
    public function index(){
        return NaveResource::collection(Nave::with('aerolinea')->get());
    }
    public function show(Nave $nave){
        return new NaveResource($nave->load('aerolinea'));
    }
    public function store(Request $request){
        $datos = $request->validate([
            'aerolinea_id' => 'nullable|exists:aerolineas,id',
            'matricula'    => 'required|string|max:10|unique:naves,matricula',
            'modelo'       => 'required|string|max:50',
            'capacidad'    => 'required|integer|min:1',
            'estado'       => 'nullable|in:activo,mantenimiento,retirado',
        ]);
        $nave = Nave::create($datos);
        return response()->json(['mensaje'=>'Nave registrada.','data'=>new NaveResource($nave)],201);
    }
    public function update(Request $request, Nave $nave){
        $datos = $request->validate([
            'matricula' => 'sometimes|string|max:10|unique:naves,matricula,'.$nave->id,
            'modelo'    => 'sometimes|string|max:50',
            'capacidad' => 'sometimes|integer|min:1',
            'estado'    => 'sometimes|in:activo,mantenimiento,retirado',
        ]);
        $nave->update($datos);
        return response()->json(['mensaje'=>'Nave actualizada.','data'=>new NaveResource($nave)]);
    }
    public function destroy(Nave $nave){
        $nave->delete();
        return response()->json(['mensaje'=>'Nave eliminada.']);
    }
}
