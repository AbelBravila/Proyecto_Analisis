<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class PedidosController extends Controller
{
    public function index_pedidos()
    {
        return view('admin.pedidos');
    }
    public function store()
    {
        return view('admin.pedidos');
    }
    public function buscar(Request $request)
    {
        $request->validate([
            'codigo_producto' => 'required|string|max:15',
        ]);
    
        $codigo_producto = $request->input('codigo_producto');
        
        $data = DB::table('esquema_producto')
            ->where('codigo_producto', $codigo_producto)
            ->select('nombre_producto')
            ->first();
    
        if (!$data) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    
        return response()->json(['nombre_producto' => $data->nombre_producto]);
    }
    
    
}
