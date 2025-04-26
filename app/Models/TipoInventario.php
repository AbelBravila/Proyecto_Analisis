<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInventario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_inventario'; // Clave primaria personalizada
    protected $table = 'tipo_inventario'; // Nombre de la tabla en la base de datos
    public $timestamps = true; // Habilitar created_at y updated_at automáticamente

    protected $fillable = [
        'nombre_tipo_inventario', // Nombre del tipo de inventario
        'estado' // Estado (activo o inactivo)
    ];

    // Relación con el modelo 'Inventario'
    public function inventarios()
    {
        return $this->hasMany(TipoInventario::class, 'id_tipo_inventario'); // Relación uno a muchos con Inventario
    }

    // Scope para filtrar los tipos de inventario activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A'); // Filtrar por estado 'A' (activo)
    }
}
