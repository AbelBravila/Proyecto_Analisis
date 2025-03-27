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
        $data = DB::select('SELECT E.nombre_producto FROM esquema_producto E JOIN producto P ON E.id_esquema_producto = P.id_esquema_producto WHERE E.codigo_producto = ?', [$codigo_producto]);
    
        if (empty($data)) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    
        return response()->json(['nombre_producto' => $data[0]->nombre_producto]);
    
    
    }
}
