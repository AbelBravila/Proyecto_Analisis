<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevolucionVenta; // Modelo por crear
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleDevolucionVenta;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DevolucionVentaController extends Controller
{
        public function exportarPDF($id)
    {
        $devolucion = DevolucionVenta::with('detalles.producto.esquema')->findOrFail($id);

        $pdf = Pdf::loadView('devoluciones_venta.pdf', compact('devolucion'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("devolucion_Venta_{$devolucion->id_devolucion_venta}.pdf");
    }
    public function index()
    {
        $devoluciones = DevolucionVenta::with(['detalles.producto.esquema', 'cliente']) // Cambio aquí
        ->orderBy('fecha_devolucion_venta', 'desc')
        ->paginate(10);

    $clientes = Cliente::where('estado', 'A')->get();

    return view('devoluciones_venta.index', compact('devoluciones', 'clientes'));
    }

    public function create()
    {
        $idUsuario = Auth::id();

        $aperturaCaja = DB::table('Apertura_Caja')
            ->join('asignacion_sat', 'Apertura_Caja.ID_Asignacion', '=', 'asignacion_sat.id_asignacion')
            ->where('asignacion_sat.id_usuario', $idUsuario)
            ->where('Apertura_Caja.Estado', 'A')
            ->exists();

        if (!$aperturaCaja) {
            return redirect()->route('devoluciones_venta.index')->with('error', 'No tiene una caja aperturada. No puede realizar una devolución.');
        }
        // Obtener todos los clientes para el select
        $clientes = Cliente::orderBy('nombre_cliente')->get();

        // Obtener las ventas (puedes filtrar por estado activo)
        $ventas = Venta::with('cliente')->get();

        return view('devoluciones_venta.create', compact('clientes', 'ventas'));
    }

public function store(Request $request)
{
    $request->validate([
        'id_venta' => 'required|exists:venta,id_venta',
        'id_cliente' => 'required|exists:cliente,id_cliente',
    ]);

    $idUsuario = Auth::id();
    $productos = json_decode($request->productos);
    $productosTable = [];

    // Validación: Caja aperturada
    $idApertura = DB::table('Apertura_Caja as ac')
        ->join('asignacion_sat as a', 'ac.ID_Asignacion', '=', 'a.id_asignacion')
        ->where('a.id_usuario', $idUsuario)
        ->where('ac.Estado', 'A')
        ->value('ac.ID_Apertura');

    if (!$idApertura) {
        return response()->json(['error' => true, 'message' => 'El usuario no tiene una caja aperturada.']);
    }

    // Validación: Productos seleccionados
    if (!$productos || count($productos) === 0) {
        return response()->json(['error' => true, 'message' => 'No hay productos seleccionados para devolver.']);
    }

    foreach ($productos as $producto) {
        if ($producto->cantidad <= 0) continue;

        $productosTable[] = [
            'id_producto' => $producto->id_producto,
            'cantidad' => $producto->cantidad,
            'tipo_devolucion' => $producto->tipo_devolucion,
            'producto_cambio_id' => $producto->tipo_devolucion === 'C' ? $producto->producto_cambio_id : null,
            'cantidad_cambio' => $producto->tipo_devolucion === 'C' ? $producto->cantidad_cambio : null,
            'danado' => $producto->danado,
            'precio' => $producto->precio,
            'id_presentacion_venta' => null,
            'id_presentacion_cambio' => null,
        ];
    }

    if (count($productosTable) === 0) {
        return response()->json(['error' => true, 'message' => 'No hay productos válidos con cantidad mayor a 0.']);
    }

    // Validación: Cantidad a devolver no mayor a vendida
    foreach ($productosTable as $p) {
        $vendida = DB::table('detalle_venta')
            ->where('id_venta', $request->id_venta)
            ->where('id_producto', $p['id_producto'])
            ->value('cantidad');

        if ($vendida === null) {
            return response()->json(['error' => true, 'message' => "El producto con ID {$p['id_producto']} no pertenece a la venta."]);
        }

        if ($p['cantidad'] > $vendida) {
            return response()->json(['error' => true, 'message' => "La cantidad a devolver del producto {$p['id_producto']} excede la cantidad vendida."]);
        }
    }

    // Validación: Stock suficiente para productos de cambio
    foreach ($productosTable as $p) {
        if ($p['tipo_devolucion'] === 'C' && $p['producto_cambio_id']) {
            $stock = DB::table('producto')
                ->where('id_producto', $p['producto_cambio_id'])
                ->value('stock');

            if ($stock === null) {
                return response()->json(['error' => true, 'message' => "El producto de cambio con ID {$p['producto_cambio_id']} no existe."]);
            }

            if ($p['cantidad_cambio'] > $stock) {
                return response()->json(['error' => true, 'message' => "No hay suficiente stock para el producto de cambio con ID {$p['producto_cambio_id']}."]);
            }
        }
    }

    DB::beginTransaction();

    try {
        DB::statement("
            DECLARE @Detalles TipoDetalleDevolucion;
            INSERT INTO @Detalles (
                id_producto, cantidad, precio, id_presentacion_venta, 
                tipo_devolucion, producto_cambio_id, cantidad_cambio, 
                id_presentacion_cambio, danado
            )
            VALUES " . implode(', ', array_map(function ($producto) {
                $values = [
                    $producto['id_producto'],
                    $producto['cantidad'],
                    $producto['precio'],
                    $producto['id_presentacion_venta'] ?? 1,
                    "'" . $producto['tipo_devolucion'] . "'",
                    $producto['producto_cambio_id'] ?? 'NULL',
                    $producto['cantidad_cambio'] ?? 'NULL',
                    $producto['id_presentacion_cambio'] ?? 'NULL',
                    $producto['danado'] ? 1 : 0
                ];
                return "(" . implode(', ', $values) . ")";
            }, $productosTable)) . ";

            DECLARE @error_message VARCHAR(255);

            EXEC sp_RealizarDevolucionVenta
                @id_venta = ?,
                @id_cliente = ?,
                @id_usuario = ?,
                @fecha_devolucion = ?,
                @detalles = @Detalles,
                @p_error_message = @error_message OUTPUT;

            SELECT @error_message AS error_message;
        ", [
            $request->id_venta,
            $request->id_cliente,
            $idUsuario,
            now()->toDateTimeString()
        ]);

        DB::commit();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Devolución registrada correctamente.']);
        }

        return redirect()->route('devoluciones.index')->with('success', 'Devolución registrada correctamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        if ($request->ajax()) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
        return back()->with('error', 'Error al registrar la devolución: ' . $e->getMessage());
    }
}


    /*
public function store(Request $request)
{
    $request->validate([
        'id_cliente' => 'required|integer|exists:cliente,id_cliente',
        'productos' => 'required|string' // Validamos como string primero
    ]);

    // Decodificar el JSON de productos
    $productos = json_decode($request->productos, true);
    
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($productos)) {
        return response()->json([
            'success' => false,
            'message' => 'Formato inválido para los productos'
        ], 400);
    }

    // Validar cada producto
    foreach ($productos as $producto) {
        $validator = Validator::make($producto, [
            'id_producto' => 'required|integer|exists:producto,id_producto',
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'tipo_devolucion' => 'required|in:N,C',
            'danado' => 'required|boolean',
            'producto_cambio_id' => 'nullable|required_if:tipo_devolucion,C|integer|exists:producto,id_producto',
            'cantidad_cambio' => 'nullable|required_if:tipo_devolucion,C|integer|min:1',
            'diferencia_precio' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de producto inválidos: ' . $validator->errors()->first()
            ], 400);
        }
    }

    try {
        // Preparar los detalles para el procedimiento almacenado
        $detallesJson = json_encode($productos);

        // Ejecutar el procedimiento almacenado
        $idDevolucion = null;
        
        DB::statement(
            'EXEC sp_GuardarDevolucionVenta @id_cliente = ?, @fecha_devolucion_venta = ?, @detalles_devolucion = ?, @id_devolucion_venta = ? OUTPUT', 
            [
                $request->id_cliente,
                now(),
                $detallesJson,
                &$idDevolucion
            ]
        );

        return response()->json([
            'success' => true,
            'id_devolucion' => $idDevolucion,
            'message' => 'Devolución registrada correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al registrar la devolución: ' . $e->getMessage()
        ], 500);
    }
}*/

    public function detalleVenta($id)
    {
        $venta = DB::table('vw_ventas_completa')
        ->where('id_venta', $id)
        ->first();

        if (!$venta) {
            return redirect()->route('devoluciones_venta.index')
                ->with('error', 'La venta no existe');
        }

        $detallesVenta = DB::table('vw_ventas_completa')
            ->where('id_venta', $id)
            ->get();

        // Obtener productos disponibles para cambio
        $productosDisponibles = DB::table('vw_producto_esq')
            ->where('estado', 'A') 
            ->get();

        return view('devoluciones_venta.detalle-venta', compact('venta', 'detallesVenta', 'productosDisponibles'));
    }

    public function show($id)
    {
        $devolucion = DevolucionVenta::with(['detalles.producto.esquema', 'venta.cliente','venta.detallesVenta.producto'])
            ->findOrFail($id);

        return view('devoluciones_venta.show', compact('devolucion'));
    }

    // Método para procesar la solicitud AJAX de la vista
    public function buscar(Request $request)
    {
        // Este método es el que se llama desde el JavaScript
        // Validar los datos de entrada
        $request->validate([
            'cliente' => 'nullable',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        $cliente = $request->cliente;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        // Construir consulta base usando la vista vw_ventas_completa
        $query = DB::table('vw_ventas_completa')
            ->select(
                'id_venta',
                'fecha_venta_formateada as fecha_venta',
                'fecha_sin_hora',
                'nombre_tipo_venta',
                'nombre_cliente',
                'nit_cliente',
                'nombre_producto',
                'descripcion_producto',
                'presentacion',
                'cantidad',
                'precio',
                'subtotal'
            );

        if ($cliente) {
            $query->where('id_cliente', $cliente);
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha_sin_hora', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->where('fecha_sin_hora', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->where('fecha_sin_hora', '<=', $fechaFin);
        }

        $ventasRaw = $query->get();

        // Agrupar productos por venta
        $ventas = [];
        foreach ($ventasRaw as $item) {
            $idVenta = $item->id_venta;

            if (!isset($ventas[$idVenta])) {
                $ventas[$idVenta] = [
                    'id_venta' => $item->id_venta,
                    'fecha_venta' => $item->fecha_venta,
                    'nombre_tipo_venta' => $item->nombre_tipo_venta,
                    'nombre_cliente' => $item->nombre_cliente,
                    'productos' => [],
                    'total' => 0,
                ];
            }

            $subtotalProducto = $item->cantidad * $item->precio;
            $ventas[$idVenta]['productos'][] = [
                'nombre_producto' => $item->nombre_producto,
                'descripcion_producto' => $item->descripcion_producto,
                'presentacion' => $item->presentacion,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio,
                'subtotal' => $subtotalProducto,
            ];

            $ventas[$idVenta]['total'] += $subtotalProducto;
        }

        return response()->json(array_values($ventas));
    }
}
/*
    private function calcularDiferencia($producto)
    {
        if ($producto['tipo_devolucion'] == 'C' && isset($producto['producto_cambio_id'])) {
            $precioNuevo = DB::table('producto')
                ->where('id_producto', $producto['producto_cambio_id'])
                ->value('precio');
                
            return ($precioNuevo - $producto['precio']) * $producto['cantidad'];
        }
        return 0;
    }

    private function procesarCambioProducto($producto)
    {
        // Disminuir stock del producto nuevo
        DB::table('producto')
            ->where('id_producto', $producto['producto_cambio_id'])
            ->decrement('stock', $producto['cantidad']);
    }

    private function registrarBaja($producto)
    {
        DB::table('alta_baja')->insert([
            'cantidad' => $producto['cantidad'],
            'id_detalle_inventario' => $this->obtenerDetalleInventario($producto['id_producto']),
            'fecha_resgistro' => now(),
            'estado' => 'A',
            // Tipo de baja?
        ]);
    }

    private function aumentarStock($producto)
    {
        DB::table('producto')
            ->where('id_producto', $producto['id_producto'])
            ->increment('stock', $producto['cantidad']);
    }

    private function registrarMovimientoCaja($diferencia)
    {
        $aperturaId = $this->obtenerAperturaActiva(); // Necesitas implementar esta función
        
        DB::table('movimientos_caja')->insert([
            'id_apertura' => $aperturaId,
            'tipo' => $diferencia > 0 ? 'INGRESO' : 'EGRESO',
            'descripcion' => 'Diferencia por cambio en devolución',
            'monto' => abs($diferencia),
            'fecha' => now()
        ]);
    }*/


