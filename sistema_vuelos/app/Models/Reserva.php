<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['usuario_id','vuelo_id','codigo_reserva','cantidad_pasajeros','total','estado','clase','asientos'];

    public function usuario(){ return $this->belongsTo(Usuario::class,'usuario_id'); }
    public function vuelo()  { return $this->belongsTo(Vuelo::class,'vuelo_id'); }
    public function pasajeros() { return $this->hasMany(Pasajero::class,'reserva_id'); }
}
