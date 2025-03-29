<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lote';
    protected $primaryKey = 'id_lote';
    
    protected $fillable = [
        'lote',
        'fabricante',
        'fecha_fabricacion',
        'fecha_vencimiento',
        'estado'
    ];
    
    protected $casts = [
        'fecha_fabricacion' => 'datetime',
        'fecha_vencimiento' => 'datetime'
    ];
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_lote');
    }
}