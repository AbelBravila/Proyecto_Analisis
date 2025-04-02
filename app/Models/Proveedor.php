<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $primaryKey = 'id_proveedor';
    
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
    