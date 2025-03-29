<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    
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