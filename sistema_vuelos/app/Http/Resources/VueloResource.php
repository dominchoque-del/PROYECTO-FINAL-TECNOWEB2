<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VueloResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'numero_vuelo'  => $this->numero_vuelo,
            'origen'        => $this->origen,
            'destino'       => $this->destino,
            'aerolinea_id'  => $this->aerolinea_id,
            'aerolinea'     => $this->whenLoaded('aerolinea', fn() => $this->aerolinea->nombre),
            'estado'        => $this->estado,
            'precio_base'   => $this->precio_base,
            'precios'       => [
                'economica' => $this->precio_economica,
                'business'  => $this->precio_business,
                'primera'   => $this->precio_primera,
            ],
            'fecha_salida'  => $this->fecha_salida,
            'fecha_llegada' => $this->fecha_llegada,
            'total_pasajeros' => $this->whenLoaded('pasajeros', fn() => $this->pasajeros->count()),
        ];
    }
}
