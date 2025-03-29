<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\DetalleDevolucion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    public function index()
    {
        $devoluciones = Devolucion::with('detalles.producto')
        ->orderBy('fecha_devolucion', 'desc')
        ->get();
        $productos = Producto::with('esquema')->where('estado', 'A')->get();
    
        return view('devoluciones.index', [
            'devoluciones' => $devoluciones,
            'productos' => $productos
        ]);
    }

    public function create()
    {
        $productos = Producto::with('esquema')
        ->where('estado', 'A')
        ->get()
        ->map(function($producto) {
            return [
                'id_producto' => $producto->id_producto,
                'nombre' => $producto->esquema->nombre_producto,
                'codigo' => $producto->esquema->codigo_producto,
                'stock' => $producto->stock,
                'costo' => $producto->costo,
                'precio' => $producto->precio
            ];
        });

        return view('devoluciones.create', compact('productos'));   
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
    
    try {
        $devolucion = Devolucion::create([
            'fecha_devolucion' => $request->fecha,
            'estado' => 'A'
        ]);
        
        foreach ($request->productos as $producto) {
            DetalleDevolucion::create([
                'cantidad' => $producto['cantidad'],
                'costo' => $producto['costo'],
                'id_producto' => $producto['id_producto'],
                'id_devolucion' => $devolucion->id_devolucion,
                'estado' => 'A'
            ]);
            
            // Actualizar stock del producto
            Producto::where('id_producto', $producto['id_producto'])
                ->increment('stock', $producto['cantidad']);
        }
        
        DB::commit();
        
        return redirect()->route('devoluciones.index')
               ->with('success', 'Devolución registrada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $devolucion = Devolucion::with('detalles.producto')->findOrFail($id);
        return view('devoluciones.show', compact('devolucion'));
    }
}
