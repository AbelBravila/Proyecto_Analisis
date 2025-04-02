<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devolucion;
use App\Models\DetalleDevolucion;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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
        // Obtener todos los proveedores para el select
        $proveedores = Proveedor::orderBy('nombre_proveedor')->get();
    
        return view('devoluciones.create', compact('proveedores'));
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
        $proveedorId = $request->get('proveedor_id');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $startDate = Carbon::parse($fechaInicio)->startOfDay()->format('Y-m-d H:i:s.000');
        $endDate = Carbon::parse($fechaFin)->endOfDay()->format('Y-m-d H:i:s.999');

        $query = Compra::with(['tipoCompra', 'detalle.producto.esquema', 'detalle.producto.proveedor', 'detalle.producto.lote'])
            ->whereBetween('fecha_compra', [$startDate, $endDate]);
        
 
        
        // Si se especifica un proveedor, filtramos las compras que tengan productos de ese proveedor
        if ($proveedorId) {
            $query->whereHas('detalle.producto', function ($q) use ($proveedorId) {
                $q->where('id_proveedor', $proveedorId);
            });
        }
        
        $compras = $query->orderBy('fecha_compra', 'desc')->get();
        
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