<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $primaryKey = 'id_compra';
    protected $table = 'compra';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo_compra',
        'fecha_compra',
        'estado'
    ];

    protected $dates = ['fecha_compra'];

    // Relación con TipoCompra (ajustada para ser opcional)
    public function tipoCompra()
    {
        return $this->belongsTo(TipoCompra::class, 'id_tipo_compra')->withDefault([
            'nombre_tipo_compra' => 'Sin tipo'
        ]);
    }

    // Relación con Proveedor (asumiendo que existe el modelo Proveedor)
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor')->withDefault([
            'nombre_proveedor' => 'Proveedor no especificado'
        ]);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }

    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class, 'id_compra');
    }
}