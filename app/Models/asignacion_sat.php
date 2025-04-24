<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asignacion_sat extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $primaryKey = 'id_asignacion';
    protected $table = 'asignacion_sat'; 
    protected $fillable = ['id_asignacion', 'id_caja', 'id_usuario', 'fecha_asignacion', 'estado'];
}
