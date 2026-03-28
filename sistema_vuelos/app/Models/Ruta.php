<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $fillable = ['origen','destino','codigo_origen','codigo_destino','distancia_km','duracion_min'];

    public function vuelos(){ return $this->hasMany(Vuelo::class,'ruta_id'); }
}
