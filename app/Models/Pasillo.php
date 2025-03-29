<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasillo extends Model
{
    protected $table = 'pasillo';
    protected $primaryKey = 'id_pasillo';
    
    protected $fillable = [
        'codigo_pasillo',
        'estado'
    ];
    
    // Relación con Estanterías
    public function estanterias()
    {
        return $this->hasMany(Estanteria::class, 'id_pasillo');
    }
    
    // Scope para pasillos activos
    public function scopeActivos($query)
    {
        return $query->where('estado', 'A');
    }
}