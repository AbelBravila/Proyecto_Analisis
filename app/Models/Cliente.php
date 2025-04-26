<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
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
    