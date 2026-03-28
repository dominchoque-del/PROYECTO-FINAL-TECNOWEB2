<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aerolinea extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre','codigo','pais'];

    public function vuelos() { return $this->hasMany(Vuelo::class, 'aerolinea_id'); }
    public function naves()  { return $this->hasMany(Nave::class,  'aerolinea_id'); }
}
