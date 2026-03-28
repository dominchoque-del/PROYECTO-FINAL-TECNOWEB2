<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonitoreoVuelo;
use App\Models\Vuelo;

class MonitoreoController extends Controller
{
    // GET /api/monitoreo — último estado de todos los vuelos activos
    public function index()
    {
        $datos = MonitoreoVuelo::with('vuelo.aerolinea')
            ->orderByDesc('registrado_en')
            ->get()
            ->unique('vuelo_id')
            ->values();
        return response()->json(['status'=>'success','data'=> $datos]);
    }

    // POST /api/monitoreo — registrar posición de un vuelo
    public function store(Request $request)
    {
        $datos = $request->validate([
            'vuelo_id'       => 'required|exists:vuelos,id',
            'latitud'        => 'nullable|numeric',
            'longitud'       => 'nullable|numeric',
            'altitud_metros' => 'nullable|integer',
            'velocidad_kmh'  => 'nullable|integer',
            'estado_actual'  => 'nullable|string',
        ]);
        $m = MonitoreoVuelo::create($datos);
        // Actualizar estado del vuelo
        if (!empty($datos['estado_actual'])) {
            Vuelo::where('id', $datos['vuelo_id'])
                 ->update(['estado' => $datos['estado_actual'] === 'En tierra' ? 'aterrizado' : 'en_vuelo']);
        }
        return response()->json(['status'=>'success','data'=> $m], 201);
    }

    // GET /api/monitoreo/{vuelo_id}/historial
    public function historial($vuelo_id)
    {
        $historial = MonitoreoVuelo::where('vuelo_id', $vuelo_id)
            ->orderByDesc('registrado_en')->take(50)->get();
        return response()->json(['status'=>'success','data'=> $historial]);
    }
}
