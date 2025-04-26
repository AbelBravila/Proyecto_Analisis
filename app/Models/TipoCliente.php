<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $primaryKey = 'id_tipo_cliente'; // Clave primaria personalizada
    protected $table = 'tipo_cliente'; // Nombre de la tabla en la base de datos
    public $timestamps = true; // Habilitar created_at y updated_at automáticamente

    protected $fillable = [
        'nombre_tipo_cliente', // Nombre del tipo de cleinte
        'estado' // Estado (activo o inactivo)
    ];

    // Relación con el modelo 'Cliente'
    public function tipos_cliente()
    {
        return $this->hasMany(TipoCliente::class, 'id_tipo_cliente'); // Relación uno a muchos con Tipo cleinte
    }

    // Scope para filtrar los tipos de clientes activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A'); // Filtrar por estado 'A' (activo)
=======
    protected $table = 'tipo_cliente';
    protected $primaryKey = 'id_tipo_cliente';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_cliente',
        'descuento',
        'estado',
    ];
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_tipo_cliente');
>>>>>>> 701b7b9d4f7cce2af03047f9ac0d959042d6205e
    }
}
