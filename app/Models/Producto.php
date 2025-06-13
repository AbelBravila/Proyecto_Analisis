<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    
    protected $fillable = [
        'id_esquema_producto',
        'precio',
        'costo',
        'stock',
        'id_lote',
        'id_proveedor',
        'id_presentacion',
        'id_estanteria',
        'id_empresa',
        'fecha_registro',
        'estado',
        'oferta'
    ];
    
    protected $casts = [
        'precio' => 'decimal:2',
        'costo' => 'decimal:2',
        'fecha_registro' => 'datetime'
    ];
    
    // Relación con EsquemaProducto
    public function esquema()
    {
        return $this->belongsTo(EsquemaProducto::class, 'id_esquema_producto');
    }
    
    // Relación con Lote
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote');
    }
    
    // Relación con Proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }
    
    // Relación con Presentacion
    public function presentacion()
    {
        return $this->belongsTo(Presentacion::class, 'id_presentacion');
    }
    
    // Relación con Estanteria
    public function estanteria()
    {
        return $this->belongsTo(Estanteria::class, 'id_estanteria');
    }
    
    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }
    
    // Relación con DetalleDevolucion (para saber en qué devoluciones aparece)
    public function devoluciones()
    {
        return $this->hasMany(DetalleDevolucion::class, 'id_producto');
    }
    
    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A');
    }
    
    // Método para obtener el nombre del producto
    public function getNombreAttribute()
    {
        return $this->esquema->nombre_producto ?? 'Producto sin nombre';
    }
    
    // Método para obtener el código del producto
    public function getCodigoAttribute()
    {
        return $this->esquema->codigo_producto ?? 'SIN CODIGO';
    }
}