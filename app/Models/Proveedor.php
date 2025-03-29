<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'proveedor';

    // Indicar que usaremos los timestamps automáticamente
    public $timestamps = true;

    // Define las columnas que se pueden insertar en la base de datos
    protected $fillable = [
        'nombre_proveedor',
        'nit',
        'correo',
        'telefono',
        'direccion',
    ];

    // Si deseas desactivar los timestamps por alguna razón, puedes hacerlo así:
    // public $timestamps = false;
    // Especifica que la clave primaria es 'id_proveedor' en lugar de 'id'
    protected $primaryKey = 'id_proveedor';

    // Si tu columna 'id_proveedor' no es auto-incremental (aunque debería serlo)
    public $incrementing = true;
}
