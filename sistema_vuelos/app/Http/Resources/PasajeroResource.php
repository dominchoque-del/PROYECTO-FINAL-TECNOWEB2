<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasajeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'reserva_id'     => $this->reserva_id,
            'nombre_completo'=> $this->nombre_completo,
            'dni'            => $this->dni,
            'email'          => $this->email,
            'clase'          => $this->clase,
            'asiento'        => $this->asiento,
            'estado_reserva' => $this->estado_reserva,
            'vuelo_id'       => $this->vuelo_id,
            'reserva'        => $this->whenLoaded('reserva', fn() => [
                'codigo_reserva' => $this->reserva->codigo_reserva,
            ]),
            'vuelo'          => $this->whenLoaded('vuelo', fn() => [
                'numero_vuelo' => $this->vuelo->numero_vuelo,
                'destino'      => $this->vuelo->destino,
            ]),
        ];
    }
}
