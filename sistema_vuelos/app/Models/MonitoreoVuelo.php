<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MonitoreoVuelo extends Model
{
    public $timestamps = false;
    protected $table    = 'monitoreo_vuelos';
    protected $fillable = ['vuelo_id','latitud','longitud','altitud_metros','velocidad_kmh','estado_actual','registrado_en'];

    public function vuelo(){ return $this->belongsTo(Vuelo::class,'vuelo_id'); }
}
