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
        // Validar los datos de entrada
        $request->validate([
            'proveedor' => 'nullable|integer',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        // Obtener los valores del formulario
        $proveedor = $request->input('proveedor');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Construir la consulta base
        $query = DB::table('vw_compras_completa')
            ->select(
                'id_compra',
                'fecha_compra_formateada as fecha_compra',
                'fecha_sin_hora',
                'nombre_tipo_compra',
                'nombre_proveedor',
                'nombre_producto',
                'descripcion_producto',
                'presentacion',
                'cantidad',
                'costo_unitario',
                'subtotal'
            );

        // Aplicar filtro por proveedor
        if ($proveedor) {
            $query->where('id_proveedor', $proveedor);
        }

        // Aplicar filtro por fechas
        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_sin_hora', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->where('fecha_sin_hora', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->where('fecha_sin_hora', '<=', $fechaFin);
        }

        // Ejecutar la consulta y obtener los resultados
        $comprasRaw = $query->get();

        // Agrupar los productos por compra
        $compras = [];
        foreach ($comprasRaw as $item) {
            $idCompra = $item->id_compra;

            if (!isset($compras[$idCompra])) {
                $compras[$idCompra] = [
                    'id_compra' => $item->id_compra,
                    'fecha_compra' => $item->fecha_compra,
                    'nombre_tipo_compra' => $item->nombre_tipo_compra,
                    'nombre_proveedor' => $item->nombre_proveedor,
                    'productos' => [],
                    'total' => 0, // Inicializamos el total en 0
                ];
            }

            // Calcular el subtotal del producto (cantidad * costo_unitario)
            $subtotalProducto = $item->cantidad * $item->costo_unitario;

            // Agregar el producto a la lista de productos de la compra
            $compras[$idCompra]['productos'][] = [
                'nombre_producto' => $item->nombre_producto,
                'descripcion_producto' => $item->descripcion_producto,
                'presentacion' => $item->presentacion,
                'cantidad' => $item->cantidad,
                'costo_unitario' => $item->costo_unitario,
                'subtotal' => $subtotalProducto, // Usamos el subtotal calculado
            ];

            // Sumar el subtotal del producto al total de la compra
            $compras[$idCompra]['total'] += $subtotalProducto;
        }

        // Convertir el array asociativo a un array indexado
        $compras = array_values($compras);

        // Retornar los resultados como JSON
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