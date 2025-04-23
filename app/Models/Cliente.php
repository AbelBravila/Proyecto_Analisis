<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente'; // Nombre de la tabla
    protected $primaryKey = 'id_cliente'; // Clave primaria
    public $timestamps = false; // Si no usas timestamps
    
    protected $fillable = [
        'nombre_cliente',
        'estado', 
    ];

    // Relación con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_cliente');
    }
    public function tipoCliente()
    {
        return $this->belongsTo(TipoCliente::class, 'id_tipo_cliente');
    }
}
