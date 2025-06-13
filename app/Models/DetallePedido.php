<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';
    protected $primaryKey = 'id_detalle_pedido';
    public $timestamps = false;

    protected $fillable = [
        'cantidad',
        'costo',
        'id_producto',
        'id_pedido',
        'estado',
    ];

    // Relación con el modelo Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    // Relación con el modelo Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}

