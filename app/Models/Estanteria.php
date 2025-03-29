<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estanteria extends Model
{
    protected $table = 'estanteria';
    protected $primaryKey = 'id_estanteria';
    
    protected $fillable = [
        'codigo_estanteria',
        'id_pasillo',
        'estado'
    ];
    
    // Relación con Pasillo
    public function pasillo()
    {
        return $this->belongsTo(Pasillo::class, 'id_pasillo');
    }
    
    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_estanteria');
    }
    
    // Scope para estanterías activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'A');
    }
}