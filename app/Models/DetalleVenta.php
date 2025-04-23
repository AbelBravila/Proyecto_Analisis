<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta'; // Nombre de la tabla
    protected $primaryKey = 'id_detalle_venta'; // Clave primaria
    public $timestamps = false; // Si no usas timestamps (created_at, updated_at)
    
    // Campos de la tabla
    protected $fillable = [
        'cantidad', 
        'precio', 
        'id_producto', 
        'id_venta', 
        'estado'
    ];

    // Relación con la venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
