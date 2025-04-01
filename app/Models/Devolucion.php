<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $primaryKey = 'id_devolucion';
    protected $table = 'devolucion';
    public $timestamps = false;

    protected $fillable = [
        'fecha_devolucion',
        'estado',
        'motivo',
        'id_compra'
    ];

    protected $dates = ['fecha_devolucion'];

    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleDevolucion::class, 'id_devolucion');
    }
}
