<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleDevolucion extends Model
{
    protected $table = 'detalle_devolucion';
    protected $primaryKey = 'id_detalle_devolucion';
    
    protected $fillable = [
        'cantidad',
        'costo',
        'id_producto',
        'id_devolucion',
        'estado'
    ];
    
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
    
    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }
}