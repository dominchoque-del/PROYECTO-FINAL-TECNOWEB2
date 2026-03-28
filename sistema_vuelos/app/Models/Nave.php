<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Nave extends Model
{
    protected $fillable = ['aerolinea_id','matricula','modelo','capacidad','estado'];

    public function aerolinea(){ return $this->belongsTo(Aerolinea::class,'aerolinea_id'); }
    public function vuelos()   { return $this->hasMany(Vuelo::class,'nave_id'); }
}
