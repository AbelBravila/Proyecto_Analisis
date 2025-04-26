<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    // Especificamos el nombre de la tabla en la base de datos (si es diferente al modelo plural)
    protected $table = 'tipo_documento';

    // Definimos los campos que se pueden asignar de manera masiva (mass assignable)
    protected $fillable = [
        'nombre_documento',
        'estado'
    ];

    // Relación con el modelo Venta
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_tipo_documento');
    }
}

