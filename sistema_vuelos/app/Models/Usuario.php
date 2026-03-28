<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre','email','password','rol','token_verificacion','email_verificado','token_reset','token_reset_expira'];
    protected $hidden   = ['password','token_verificacion','token_reset'];

    public function reservas() { return $this->hasMany(Reserva::class, 'usuario_id'); }
    public function pasajeros(){ return $this->hasMany(Pasajero::class, 'usuario_id'); }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }
    public function verificarPassword($plain){
        return Hash::check($plain, $this->password);
    }
}
