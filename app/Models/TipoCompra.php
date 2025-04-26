<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCompra extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_compra';
    protected $table = 'tipo_compra';
    public $timestamps = true; // Ahora se registran automáticamente created_at y updated_at

    protected $fillable = [
        'nombre_tipo_compra',
        'estado'
    ];

    // Relación con Compras
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_tipo_compra');
    }

    // Scope para filtrar los tipos de compra activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A');
    }
}