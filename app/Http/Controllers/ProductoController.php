<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoController extends Controller
{
    public function index_producto()
    {
        $productos = DB::table('esquema_producto')->where('estado', 'A')->get();
        return view('compras.producto', compact('productos'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'codigo_product' => 'required|string|max:15',
            'nombre_product' => 'required|string|max:100',
            'descripcion_product' => 'required|string|max:100'
        ]);

        $codigoExistente = DB::table('esquema_producto')->where('codigo_producto', $request->codigo_product)->first();

        if ($codigoExistente) {
            return redirect()->route('producto')->with('error', 'El codigo ya está existe.');
        }

        DB::statement("EXEC sp_Insert_Esquema_Producto
            @codigo_producto = ?,
            @nombre_producto = ?,
            @descripcion =?",
            [$request->codigo_product, $request->nombre_product, $request->descripcion_product]
        );

        return redirect()->route('producto')->with('success', 'Producto registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC cambiarEstadoProducto ?', [$id]);

        return redirect()->route('producto')->with('success', 'Estado del producto actualizado');
    }
}