<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vuelo;
use App\Http\Resources\VueloResource;

class VueloApiController extends Controller
{
    public function index(){
        $vuelos = Vuelo::with(['aerolinea','pasajeros'])->get();
        return VueloResource::collection($vuelos);
    }
    public function show(Vuelo $vuelo){
        $vuelo->load(['aerolinea','pasajeros']);
        return new VueloResource($vuelo);
    }
    public function store(Request $request){
        $datos = $request->validate([
            'aerolinea_id'  => 'required|exists:aerolineas,id',
            'origen'        => 'nullable|string|max:255',
            'destino'       => 'required|string|max:255',
            'numero_vuelo'  => 'nullable|string|max:10',
            'nave_id'       => 'nullable|exists:naves,id',
            'ruta_id'       => 'nullable|exists:rutas,id',
            'fecha_salida'  => 'nullable|date',
            'fecha_llegada' => 'nullable|date',
            'precio_base'   => 'nullable|numeric|min:0',
            'precio_economica' => 'nullable|numeric|min:0',
            'precio_business'  => 'nullable|numeric|min:0',
            'precio_primera'   => 'nullable|numeric|min:0',
        ]);
        // Generar numero_vuelo automático si no se provee
        if(empty($datos['numero_vuelo'])){
            $datos['numero_vuelo'] = 'VUE-'.str_pad(Vuelo::withTrashed()->count()+1, 4, '0', STR_PAD_LEFT);
        }
        $vuelo = Vuelo::create($datos);
        return response()->json(['status'=>'success','mensaje'=>'Vuelo programado.','data'=>new VueloResource($vuelo)],201);
    }
    public function update(Request $request, Vuelo $vuelo){
        $datos = $request->validate([
            'aerolinea_id'  => 'sometimes|exists:aerolineas,id',
            'origen'        => 'sometimes|string|max:255',
            'destino'       => 'sometimes|string|max:255',
            'numero_vuelo'  => 'sometimes|string|max:10',
            'estado'        => 'sometimes|in:programado,abordando,en_vuelo,aterrizado,cancelado',
            'precio_base'   => 'sometimes|numeric|min:0',
            'precio_economica' => 'sometimes|numeric|min:0',
            'precio_business'  => 'sometimes|numeric|min:0',
            'precio_primera'   => 'sometimes|numeric|min:0',
            'fecha_salida'  => 'sometimes|nullable|date',
            'fecha_llegada' => 'sometimes|nullable|date',
        ]);
        $vuelo->update($datos);
        return response()->json(['status'=>'success','mensaje'=>'Vuelo actualizado.','data'=>new VueloResource($vuelo->load('aerolinea'))]);
    }
    public function destroy(Vuelo $vuelo){
        $vuelo->delete();
        return response()->json(['status'=>'success','mensaje'=>'Vuelo cancelado (soft delete).']);
    }
    public function restaurar($id){
        $vuelo = Vuelo::withTrashed()->findOrFail($id);
        $vuelo->restore();
        return response()->json(['status'=>'success','mensaje'=>'Vuelo restaurado.','data'=>new VueloResource($vuelo)]);
    }
}
