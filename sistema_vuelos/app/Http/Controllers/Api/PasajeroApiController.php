<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasajero;
use App\Http\Resources\PasajeroResource;

class PasajeroApiController extends Controller
{
    public function index(){
        return PasajeroResource::collection(Pasajero::with(['vuelo','reserva'])->get());
    }
    public function show(Pasajero $pasajero){
        return new PasajeroResource($pasajero->load(['vuelo','reserva']));
    }
    public function store(Request $request){
        $datos = $request->validate([
            'vuelo_id'        => 'required|exists:vuelos,id',
            'nombre_completo' => 'required|string|max:255',
            'dni'             => 'nullable|string|max:20',
            'email'           => 'nullable|email',
            'clase'           => 'nullable|in:economica,business,primera',
            'asiento'         => 'nullable|string|max:5',
            'usuario_id'      => 'nullable|exists:usuarios,id',
        ]);
        $p = Pasajero::create($datos);
        return response()->json(['status'=>'success','mensaje'=>'Pasajero registrado.','data'=>new PasajeroResource($p)],201);
    }
    public function update(Request $request, Pasajero $pasajero){
        $datos = $request->validate([
            'nombre_completo' => 'sometimes|string|max:255',
            'vuelo_id'        => 'sometimes|exists:vuelos,id',
            'clase'           => 'sometimes|in:economica,business,primera',
            'asiento'         => 'sometimes|nullable|string|max:5',
            'estado_reserva'  => 'sometimes|in:confirmada,pendiente,cancelada',
        ]);
        $pasajero->update($datos);
        return response()->json(['status'=>'success','data'=>new PasajeroResource($pasajero->load('vuelo'))]);
    }
    public function destroy(Pasajero $pasajero){
        $pasajero->delete();
        return response()->json(['status'=>'success','mensaje'=>'Pasajero eliminado.']);
    }
}
