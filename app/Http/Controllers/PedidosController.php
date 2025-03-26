<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index_pedidos()
    {
        return view('admin.pedidos');
    }
}
