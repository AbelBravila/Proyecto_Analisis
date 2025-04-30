<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevolucionVenta; // Modelo por crear
use App\Models\Venta;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DevolucionVentaController extends Controller
{
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
        // Obtener todos los clientes para el select
        $clientes = Cliente::orderBy('nombre_cliente')->get();

        // Obtener las ventas (puedes filtrar por estado activo)
        $ventas = Venta::with('cliente')->get();

        return view('devoluciones_venta.create', compact('clientes', 'ventas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_venta' => 'required|integer',
            'productos' => 'required|array',
        ]);
        
        // Filtrar productos con cantidad > 0
        $productosDevolver = [];
        
        foreach ($request->productos as $producto) {
            if (isset($producto['cantidad']) && intval($producto['cantidad']) > 0) {
                $productosDevolver[] = [
                    'nombre_producto' => $producto['nombre_producto'],
                    'cantidad' => intval($producto['cantidad'])
                ];
            }
        }
        
        if (empty($productosDevolver)) {
            return redirect()->back()
                ->with('error', 'Debe seleccionar al menos un producto para devolver.')
                ->withInput();
        }
        
        // Convertir a JSON
        $productosJSON = json_encode($productosDevolver, JSON_UNESCAPED_UNICODE);
        
        try {
            // Log para debug
            Log::info('Ejecutando devolución de venta', [
                'id_venta' => $request->id_venta,
                'productos' => $productosJSON
            ]);
            
            // Ejecutar procedimiento almacenado
            $result = DB::select('EXEC sp_RegistrarDevolucionVenta @id_venta = ?, @ProductosDevolver = ?', [
                $request->id_venta,
                $productosJSON
            ]);
            
            // Verificar el resultado
            Log::info('Resultado del procedimiento', ['result' => $result]);
            
            // Analizar mensajes de respuesta del procedimiento
            $mensajeError = null;
            foreach ($result as $row) {
                // Si alguna fila contiene un mensaje de error, lo capturamos
                if (property_exists($row, 'Mensaje') && strpos($row->Mensaje, 'Error:') === 0) {
                    $mensajeError = $row->Mensaje;
                    break;
                }
            }
            
            if ($mensajeError) {
                return redirect()->back()
                    ->with('error', $mensajeError)
                    ->withInput();
            }
            
            return redirect()->route('devoluciones_venta.index')
                ->with('success', 'Devolución de venta registrada correctamente');
                
        } catch (\Exception $e) {
            Log::error('Error al registrar devolución de venta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Error al registrar la devolución de venta: ' . $e->getMessage())
                ->withInput();
        }
    }
/*
    public function show($id)
    {
        $devolucion = DevolucionVenta::with(['detalles.producto.esquema', 'venta.cliente', 'venta.detalle.producto'])
            ->findOrFail($id);

        return view('devoluciones_venta.show', compact('devolucion'));
    }*/

    public function show($id)
    {
        $devolucion = DevolucionVenta::with(['detalles.producto.esquema', 'venta.cliente','venta.detalle.producto'])
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

    // Método original (ahora es redundante pero lo mantenemos por compatibilidad)
  /*  public function buscarVentas(Request $request)
    {
        // Este método es similar al buscar pero con una interfaz diferente
        $request->validate([
            'cliente' => 'nullable|integer',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        $cliente = $request->input('cliente');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

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

        return view('devoluciones_venta.detalle-venta', compact('venta', 'detallesVenta'));
    }
}