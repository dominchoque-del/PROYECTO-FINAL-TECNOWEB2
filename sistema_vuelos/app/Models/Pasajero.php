<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasajero extends Model
{
    use SoftDeletes;
    protected $fillable = ['reserva_id','vuelo_id','usuario_id','nombre_completo','dni','email','clase','asiento','estado_reserva'];

    public function vuelo()   { return $this->belongsTo(Vuelo::class,'vuelo_id'); }
    public function usuario() { return $this->belongsTo(Usuario::class,'usuario_id'); }
    public function reserva() { return $this->belongsTo(Reserva::class,'reserva_id'); }
}
