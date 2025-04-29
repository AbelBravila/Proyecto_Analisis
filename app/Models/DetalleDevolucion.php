<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleDevolucion extends Model
{
    protected $primaryKey = 'id_detalle_devolucion';
    protected $table = 'detalle_devolucion';
    public $timestamps = false;

    protected $fillable = [
        'id_devolucion',
        'id_producto',
        'cantidad',
        'precio'
    ];

    public function devolucion()
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}