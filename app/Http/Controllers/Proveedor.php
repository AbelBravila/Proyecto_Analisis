<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Proveedor extends Controller
{
    public function index_proveedor()
    {
        return view('admin.Proveedores');
    }
}
