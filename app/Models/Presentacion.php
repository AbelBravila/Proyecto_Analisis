<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    protected $table = 'presentacion';
    protected $primaryKey = 'id_presentacion';
    
    protected $fillable = [
        'presentacion',
        'estado'
    ];
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_presentacion');
    }
    
    // Scope para presentaciones activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'A');
    }
}