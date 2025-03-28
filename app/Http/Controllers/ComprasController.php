<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index_compras()
    {
        $productos = DB::table('esquema_producto')->where('estado', 'A')->get();
        return view('compras.producto', compact('productos'));
    }

    
}