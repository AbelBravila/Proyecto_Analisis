<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

    protected $table = 'tipo_cliente';
    protected $primaryKey = 'id_tipo_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_cliente',
        'descuento',
        'estado',
    ];
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_tipo_cliente');
    }
}
