<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenDispositivo extends Model
{
    protected $table = 'Tokens_Dispositivos';
    protected $primaryKey = 'IDTokensDispositivos';
    public $timestamps = false;

    protected $fillable = [
        'IdUsuario',
        'TokenDispositivo',
        'FechaRegistro',
        'Estado'
    ];
}
