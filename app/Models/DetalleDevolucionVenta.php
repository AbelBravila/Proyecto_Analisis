<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleDevolucionVenta extends Model
{
    protected $primaryKey = 'id_detalle_devolucion_venta';
    protected $table = 'detalle_devolucion_venta';
    public $timestamps = false;

    protected $fillable = [
        'id_devolucion_venta',
        'id_producto',
        'cantidad',
        'precio' // Ajusta según los campos reales de tu tabla
    ];

    public function devolucionVenta()
    {
        return $this->belongsTo(DevolucionVenta::class, 'id_devolucion_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}