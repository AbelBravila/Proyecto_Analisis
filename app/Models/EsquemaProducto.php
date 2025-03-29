<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsquemaProducto extends Model
{
    protected $table = 'esquema_producto';
    protected $primaryKey = 'id_esquema_producto';
    
    protected $fillable = [
        'codigo_producto',
        'nombre_producto',
        'descripcion',
        'fecha_creacion',
        'estado'
    ];
    
    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_esquema_producto');
    }
}