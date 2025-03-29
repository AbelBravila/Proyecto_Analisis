<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevolucionesController extends Controller
{
    public function index_devoluciones()
    {
        return view('admin.devoluciones');
    }
}
                            