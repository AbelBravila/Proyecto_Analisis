<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 8584a5d31e99cfc1fc787df2c4a700d422a1be3f
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
<<<<<<< HEAD
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    
=======
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'proveedor';

    // Indicar que usaremos los timestamps automáticamente
    public $timestamps = true;

    // Define las columnas que se pueden insertar en la base de datos
>>>>>>> 8584a5d31e99cfc1fc787df2c4a700d422a1be3f
    protected $fillable = [
        'nombre_proveedor',
        'nit',
        'correo',
        'telefono',
        'direccion',
<<<<<<< HEAD
        'estado'
    ];
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_proveedor');
    }
    
    // Scope para proveedores activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A');
    }
}
=======
    ];

    // Si deseas desactivar los timestamps por alguna razón, puedes hacerlo así:
    // public $timestamps = false;
    // Especifica que la clave primaria es 'id_proveedor' en lugar de 'id'
    protected $primaryKey = 'id_proveedor';

    // Si tu columna 'id_proveedor' no es auto-incremental (aunque debería serlo)
    public $incrementing = true;
}
>>>>>>> 8584a5d31e99cfc1fc787df2c4a700d422a1be3f
