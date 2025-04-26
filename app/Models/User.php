<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario'; 
    protected $primaryKey = 'id_usuario'; 

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'codigo_usuario',
        'nombre_usuario',
        'correo',
        'telefono',
        'estado',
        'id_nivel',        
        'intentos',
        'temporal',
        'dias'
    ];

    public function empresa()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_usuario', 'id_usuario', 'id_empresa');
    }


    protected $hidden = ['contrasena']; 
}
