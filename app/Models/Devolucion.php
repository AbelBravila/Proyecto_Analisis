<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucion';
    protected $primaryKey = 'id_devolucion';

    // Agrega esta línea para desactivar los timestamps
    public $timestamps = false;
    
    protected $fillable = [
        'fecha_devolucion',
        'estado'
    ];
    
    public function detalles()
    {
        return $this->hasMany(DetalleDevolucion::class, 'id_devolucion');
    }
}
