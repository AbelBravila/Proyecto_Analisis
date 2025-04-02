<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EsquemaProducto;

class ProductoController extends Controller
{
    public function index_producto()
    {
        $productos = EsquemaProducto::where('estado', 'A')->get();
        return view('compras.producto', compact('productos'));
    }

    private function validateProduct(Request $request)
    {
        $request->validate([
            'codigo_product' => 'required|string|max:15',
            'nombre_product' => 'required|string|max:100',
            'descripcion_product' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateProduct($request);

        $codigoExistente = EsquemaProducto::where('codigo_producto', $request->codigo_product)->first();

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

    public function editar_producto($id)
    {
        $productos = EsquemaProducto::findOrFail($id);

        return view('compras.producto', compact('productos'));
    }

    public function actualizar_producto(Request $request, $id)
    {
        $this->validateProduct($request);
        $productos = EsquemaProducto::findOrFail($id);
        $codigoExistente = EsquemaProducto::where('codigo_producto', $request->codigo_product)->first();

        if ($codigoExistente) {
            return redirect()->route('producto')->with('error', 'El codigo ya está existe.');
        }

        DB::statement('EXEC sp_actualizar_esquema_producto
            @id_esquema_producto = ?, 
            @codigo_producto = ?, 
            @nombre_producto = ?, 
            @descripcion = ?',
            [
                $id, 
                $request->codigo_product, 
                $request->nombre_product, 
                $request->descripcion_product
            ]
        );

        // Redirigir con mensaje de éxito
        return redirect()->route('producto')->with('success', 'Producto actualizado exitosamente.');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoEsquemaProducto ?', [$id]);

        return redirect()->route('producto')->with('success', 'Estado del producto actualizado');
    }
}