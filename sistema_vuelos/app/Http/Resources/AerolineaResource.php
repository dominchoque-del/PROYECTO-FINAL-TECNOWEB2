<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AerolineaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nombre'       => $this->nombre,
            'codigo'       => $this->codigo,
            'pais'         => $this->pais,
            'total_vuelos' => $this->whenLoaded('vuelos', fn() => $this->vuelos->count()),
        ];
    }
}
