<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $primaryKey = 'id_detalle_compra';
    protected $table = 'detalle_compra';
    public $timestamps = false;

    protected $fillable = [
        'id_compra',
        'id_producto',
        'cantidad',
        'costo',
        'estado'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}