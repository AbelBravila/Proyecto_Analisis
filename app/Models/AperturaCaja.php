<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AperturaCaja extends Model
{
    public $timestamps = true;
    use HasFactory;
    protected $table = 'apertura_caja'; // o el nombre correcto de tu tabla
    protected $fillable = ['id_caja', 'id_turno', 'monto_apertura', 'fecha_apertura', 'estado'];
}
