<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta'; // Nombre de la tabla
    protected $primaryKey = 'id_venta'; // Clave primaria
    public $timestamps = false; // Si no usas timestamps (created_at, updated_at)
    
    // Campos de la tabla
    protected $fillable = [
        'id_tipo_venta', 
        'id_tipo_pago', 
        'id_tipo_documento', 
        'id_cliente',
        'id_usuario',
        'fecha_venta',
        'subtotal_venta',
        'total_descuento',
        'total_venta',
        'estado'
    ];

    // Relación con el tipo de venta
    public function tipoVenta()
    {
        return $this->belongsTo(TipoVenta::class, 'id_tipo_venta');
    }

    // Relación con el tipo de pago
    public function tipoPago()
    {
        return $this->belongsTo(TipoPago::class, 'id_tipo_pago');
    }

    // Relación con el tipo de documento
    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo_documento');
    }

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Relación con el usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con los detalles de la venta
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }
}
