<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionVenta extends Model
{
    protected $primaryKey = 'id_devolucion_venta';
    protected $table = 'devolucion_venta';
    public $timestamps = false;

    protected $fillable = [
        'fecha_devolucion_venta',
        'id_venta',
        'estado'
    ];
    protected $dates = ['fecha_devolucion_venta'];

    public function detalles()
    {
        return $this->hasMany(DetalleDevolucionVenta::class, 'id_devolucion_venta');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta'); // Verifica que la FK sea correcta
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

}