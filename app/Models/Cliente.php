<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
<<<<<<< HEAD
    protected $primaryKey = 'id_cliente';
    
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'cliente';

    // Indicar que usaremos los timestamps automáticamente
    public $timestamps = true;

    // Define las columnas que se pueden insertar en la base de datos
    protected $fillable = [
        'id_cliente',
        'id_tipo_cliente',
        'nombre_cliente',
        'dpi',
        'nit',
        'teléfono',
        'correo',
        'estado'
    ];
    
    // Relación con Productos
    public function tipo_cliente()
    {
        return $this->hasMany(Cliente::class, 'id_cliente');
    }
    
    // Scope para proveedores activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A');
    }
}
    
=======
    use HasFactory;

    protected $table = 'cliente'; // Nombre de la tabla
    protected $primaryKey = 'id_cliente'; // Clave primaria
    public $timestamps = false; // Si no usas timestamps
    
    protected $fillable = [
        'nombre_cliente',
        'estado', 
    ];

    // Relación con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente');
    }
    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'id_tipo_cliente');
    }
}
>>>>>>> 701b7b9d4f7cce2af03047f9ac0d959042d6205e
