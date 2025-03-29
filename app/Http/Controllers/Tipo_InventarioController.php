<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Tipo_InventarioController extends Controller
{
    public function index_Tipo_Inventario()
    {
        return view('admin.Tipo_Inventario');
    }
}
