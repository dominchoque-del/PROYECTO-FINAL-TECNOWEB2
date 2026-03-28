<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'identificador' => $this->matricula,
            'matricula'     => $this->matricula,
            'modelo'        => $this->modelo,
            'capacidad'     => $this->capacidad,
            'estado'        => $this->estado,
            'aerolinea_id'  => $this->aerolinea_id,
            'aerolinea'     => $this->whenLoaded('aerolinea', fn() => $this->aerolinea->nombre),
            'info_tecnica'  => $this->modelo . ' (Capacidad: ' . $this->capacidad . ')',
        ];
    }
}
