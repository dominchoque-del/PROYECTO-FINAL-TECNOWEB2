<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Vuelo;
use App\Models\Usuario;
use App\Models\Pasajero;
use App\Services\MailService;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    protected MailService $mail;
    
    public function __construct(MailService $mail)
    {
        $this->mail = $mail;
    }

    public function index()
    {
        $reservas = Reserva::with(['usuario','vuelo.aerolinea','pasajeros'])->get();
        return response()->json(['status'=>'success','data'=> $reservas]);
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'usuario_id'          => 'required|exists:usuarios,id',
            'dni'                => 'required|string|max:20',
            'vuelo_id'           => 'required|exists:vuelos,id',
            'cantidad_pasajeros' => 'required|integer|min:1',
            'clase'              => 'nullable|in:economica,business,primera',
            'asientos'           => 'nullable|string',
        ]);

        $vuelo = Vuelo::findOrFail($datos['vuelo_id']);
        $clase = $datos['clase'] ?? 'economica';
        
        $precios = [
            'economica' => $vuelo->precio_economica ?? $vuelo->precio_base,
            'business'  => $vuelo->precio_business ?? ($vuelo->precio_base * 2),
            'primera'   => $vuelo->precio_primera ?? ($vuelo->precio_base * 3),
        ];
        
        $precioUnitario = $precios[$clase] ?? $vuelo->precio_base;
        $total = $precioUnitario * $datos['cantidad_pasajeros'];
        $codigo = 'RES-' . strtoupper(Str::random(6));

        $reserva = Reserva::create([
            'usuario_id'         => $datos['usuario_id'],
            'vuelo_id'           => $datos['vuelo_id'],
            'codigo_reserva'     => $codigo,
            'cantidad_pasajeros' => $datos['cantidad_pasajeros'],
            'total'              => $total,
            'estado'             => 'confirmada',
            'clase'              => $clase,
            'asientos'           => $datos['asientos'] ?? null,
        ]);

        $asientosArray = $datos['asientos'] ? explode(',', $datos['asientos']) : [];
        $usuario = Usuario::find($datos['usuario_id']);
        
        for ($i = 0; $i < $datos['cantidad_pasajeros']; $i++) {
            Pasajero::create([
                'reserva_id'      => $reserva->id,
                'vuelo_id'        => $datos['vuelo_id'],
                'usuario_id'      => $datos['usuario_id'],
                'nombre_completo' => $usuario->nombre ?? 'Pasajero ' . ($i + 1),
                'dni'             => $datos['dni'] ?? null,
                'email'           => $usuario->email ?? null,
                'clase'           => $clase,
                'asiento'         => isset($asientosArray[$i]) ? trim($asientosArray[$i]) : null,
                'estado_reserva'  => 'confirmada',
            ]);
        }

        if ($usuario && $usuario->email) {
            $this->mail->enviarConfirmacionReserva($usuario->email, $usuario->nombre, [
                'codigo'    => $codigo,
                'vuelo'     => $vuelo->numero_vuelo,
                'destino'   => $vuelo->destino,
                'pasajeros' => $datos['cantidad_pasajeros'],
                'total'     => number_format($total, 2),
            ]);
        }

        return response()->json(['status'=>'success','mensaje'=>'Reserva creada.','data'=> $reserva->load('pasajeros')], 201);
    }

    public function show($id)
    {
        $r = Reserva::with(['usuario','vuelo.aerolinea','pasajeros'])->findOrFail($id);
        return response()->json(['status'=>'success','data'=> $r]);
    }

    public function destroy($id)
    {
        $r = Reserva::findOrFail($id);
        $r->pasajeros()->delete();
        $r->update(['estado'=>'cancelada']);
        return response()->json(['status'=>'success','mensaje'=>'Reserva cancelada.']);
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $datos = $request->validate([
            'usuario_id'          => 'sometimes|exists:usuarios,id',
            'vuelo_id'           => 'sometimes|exists:vuelos,id',
            'cantidad_pasajeros'  => 'sometimes|integer|min:1',
            'estado'              => 'sometimes|in:pendiente,confirmada,cancelada,completada',
            'clase'              => 'sometimes|in:economica,business,primera',
            'asientos'           => 'sometimes|nullable|string',
        ]);

        if (isset($datos['vuelo_id']) && isset($datos['cantidad_pasajeros'])) {
            $vuelo = Vuelo::find($datos['vuelo_id']);
            if ($vuelo) {
                $precios = [
                    'economica' => $vuelo->precio_economica ?? $vuelo->precio_base,
                    'business'  => $vuelo->precio_business ?? ($vuelo->precio_base * 2),
                    'primera'   => $vuelo->precio_primera ?? ($vuelo->precio_base * 3),
                ];
                $clase = $datos['clase'] ?? $reserva->clase ?? 'economica';
                $precioUnitario = $precios[$clase] ?? $vuelo->precio_base;
                $datos['total'] = $precioUnitario * $datos['cantidad_pasajeros'];
            }
        }

        $reserva->update($datos);
        return response()->json(['status'=>'success','mensaje'=>'Reserva actualizada.','data'=> $reserva->load('pasajeros')]);
    }
}
