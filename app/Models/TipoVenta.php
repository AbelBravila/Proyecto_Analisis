<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVenta extends Model
{
    use HasFactory;

    protected $table = 'tipo_venta'; // Nombre de la tabla
    protected $primaryKey = 'id_tipo_venta'; // Clave primaria
    public $timestamps = false; // Si no usas timestamps
    
    protected $fillable = [
        'nombre_tipo_venta',
        'estado', 
    ];

    // Relación con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_tipo_venta');
    }
}
