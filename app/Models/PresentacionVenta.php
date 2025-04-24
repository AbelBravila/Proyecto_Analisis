<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentacionVenta extends Model
{
    // Nombre de la tabla en la base de datos
    protected $table = 'presentacion_venta';

    // La clave primaria
    protected $primaryKey = 'id_presentacion_venta';

    // Para evitar el uso de timestamps automáticos si no existen en la tabla
    public $timestamps = false;

    // Los atributos que se pueden asignar masivamente
    protected $fillable = [
        'nombre_presentacion',
        'cantidad',
        'descuento',
        'estado',
    ];

    // Relación con detalle_venta
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'id_presentacion_venta');
    }
}
