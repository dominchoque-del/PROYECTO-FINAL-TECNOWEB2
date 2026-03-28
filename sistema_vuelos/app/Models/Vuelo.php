<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vuelo extends Model
{
    use SoftDeletes;
    protected $fillable = ['aerolinea_id','nave_id','ruta_id','numero_vuelo','origen','destino','fecha_salida','fecha_llegada','estado','precio_base','precio_economica','precio_business','precio_primera'];

    public function aerolinea()  { return $this->belongsTo(Aerolinea::class,'aerolinea_id'); }
    public function nave()       { return $this->belongsTo(Nave::class,'nave_id'); }
    public function ruta()       { return $this->belongsTo(Ruta::class,'ruta_id'); }
    public function pasajeros()  { return $this->hasMany(Pasajero::class,'vuelo_id'); }
    public function reservas()   { return $this->hasMany(Reserva::class,'vuelo_id'); }
    public function monitoreos() { return $this->hasMany(MonitoreoVuelo::class,'vuelo_id'); }

    public function restaurar($id){
        $v = self::withTrashed()->findOrFail($id);
        $v->restore();
        return $v;
    }
}
