<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devolucion;
use App\Models\DetalleDevolucion;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class DevolucionController extends Controller
{
    public function index()
    {
        $devoluciones = Devolucion::with(['detalles.producto.esquema', 'compra.proveedor'])
            ->orderBy('fecha_devolucion', 'desc')
            ->paginate(10);
            
        $proveedores = Proveedor::where('estado', 'A')->get();

        return view('devoluciones.index', compact('devoluciones', 'proveedores'));
    }

    public function create()
    {
        // No necesitamos esta función si usamos el modal en index
        return redirect()->route('devoluciones.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_compra' => 'required|exists:compra,id_compra',
            'fecha_devolucion' => 'required|date',
            'motivo' => 'required|string|max:255',
            'productos' => 'required|json'
        ]);

        DB::beginTransaction();

        try {
            // Crear la devolución
            $devolucion = Devolucion::create([
                'fecha_devolucion' => $request->fecha_devolucion,
                'estado' => 'C',
                'motivo' => $request->motivo,
                'id_compra' => $request->id_compra
            ]);

            // Procesar productos devueltos
            $productos = json_decode($request->productos, true);
            
            foreach ($productos as $producto) {
                // Registrar detalle de devolución
                DetalleDevolucion::create([
                    'id_devolucion' => $devolucion->id_devolucion,
                    'id_producto' => $producto['id_producto'],
                    'cantidad' => $producto['cantidad'],
                    'costo' => $producto['costo']
                ]);

                // Actualizar stock del producto
                Producto::where('id_producto', $producto['id_producto'])
                    ->decrement('stock', $producto['cantidad']);
            }

            DB::commit();
            return response()->json(['success' => true, 'redirect' => route('devoluciones.show', $devolucion->id_devolucion)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $devolucion = Devolucion::with(['detalles.producto.esquema', 'compra.proveedor', 'compra.detalle.producto'])
            ->findOrFail($id);

        return view('devoluciones.show', compact('devolucion'));
    }

    // Nuevos métodos para el proceso AJAX
    public function buscarCompras(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedor,id_proveedor',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $compras = Compra::with(['tipoCompra', 'proveedor'])
        ->where('id_proveedor', $request->proveedor_id)
        ->whereBetween('fecha_compra', [$request->fecha_inicio, $request->fecha_fin])
        ->where('estado', 'C')
        ->get()
        ->map(function($compra) {
            return [
                'id_compra' => $compra->id_compra,
                'fecha_compra' => $compra->fecha_compra,
                'tipo_compra' => [
                    'nombre_tipo_compra' => $compra->tipoCompra->nombre_tipo_compra ?? 'Sin tipo'
                ],
                'proveedor' => $compra->proveedor,
                'total' => $compra->detalles->sum(function($item) {
                    return $item->cantidad * $item->costo;
                })
            ];
        });

        return response()->json($compras);
    }

    public function detalleCompra($id)
    {
        $compra = Compra::with(['detalle.producto.esquema', 'detalle.producto.lote'])->findOrFail($id);
        
        return response()->json([
            'fecha_compra' => $compra->fecha_compra,
            'proveedor' => $compra->proveedor,
            'detalle' => $compra->detalle->map(function($item) {
                return [
                    'id_producto' => $item->id_producto,
                    'producto' => $item->producto,
                    'cantidad' => $item->cantidad,
                    'costo' => $item->costo,
                    'stock_actual' => $item->producto->stock
                ];
            })
        ]);
    }
}