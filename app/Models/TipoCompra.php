<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCompra extends Model
{
    protected $primaryKey = 'id_tipo_compra';
    protected $table = 'tipo_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_compra',
        'estado'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_tipo_compra');
    }
}
