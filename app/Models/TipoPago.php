<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla en la base de datos (si es diferente al modelo plural)
    protected $table = 'tipo_pago';

    // Definimos los campos que se pueden asignar de manera masiva (mass assignable)
    protected $fillable = [
        'nombre_tipo_pago', 
        'estado'
    ];

    // Definir la relación con el modelo Venta
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_tipo_pago');
    }
}

