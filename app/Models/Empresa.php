<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'id_empresa';
    
    protected $fillable = [
        // Aquí debes agregar los campos de tu tabla empresa
        'nombre',
        'direccion',
        'telefono',
        'estado'
    ];
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_empresa');
    }
    
    // Scope para empresas activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'A');
    }
}