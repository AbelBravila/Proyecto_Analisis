<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\TipoVenta;
use App\Models\TipoPago;
use App\Models\TipoDocumento;
use App\Models\TipoCliente;
use App\Models\PresentacionVenta;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index_ventas(Request $request)
    {
        $buscar = $request->input('buscador');
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        $ventas = DB::table('vw_detalle_venta')
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('nombre_cliente', 'LIKE', "%{$buscar}%")
                    ->orWhere('id_venta', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($fecha_inicio && $fecha_fin, function ($query) use ($fecha_inicio, $fecha_fin) {
                return $query->whereBetween('fecha_venta', [$fecha_inicio, $fecha_fin]);
            })
            ->when($fecha_inicio && !$fecha_fin, function ($query) use ($fecha_inicio) {
                return $query->whereDate('fecha_venta', '>=', $fecha_inicio);
            })
            ->when(!$fecha_inicio && $fecha_fin, function ($query) use ($fecha_fin) {
                return $query->whereDate('fecha_venta', '<=', $fecha_fin);
            })
            ->orderBy('fecha_venta','desc')
            ->orderBy('id_venta','desc')
            ->paginate(10);

        return view('ventas.ventas', compact('ventas'));
    }

    public function index_registrar()
    {
        $idUsuario = Auth::id();

        $aperturaCaja = DB::table('Apertura_Caja')
            ->join('asignacion_sat', 'Apertura_Caja.ID_Asignacion', '=', 'asignacion_sat.id_asignacion')
            ->where('asignacion_sat.id_usuario', $idUsuario)
            ->where('Apertura_Caja.Estado', 'A')
            ->exists();

        if (!$aperturaCaja) {
            return redirect()->route('ventas')->with('error', 'No tiene una caja aperturada. No puede registrar una venta.');
        }

        $clientes = Cliente::where('estado', 'A')->get();
        $tipo_venta = TipoVenta::where('estado', 'A')->get();
        $tipo_pago = TipoPago::where('estado', 'A')->get();
        $tipo_documento = TipoDocumento::where('estado', 'A')->get();
        $productos = Producto::with(['esquema', 'lote'])->where('estado', 'A')->get();

        $productosAgrupados = $productos->groupBy('esquema.codigo_producto')->map(function($items) {
            return $items->map(function($p) {
                return [
                    'id_producto' => $p->id_producto,
                    'codigo_producto' => $p->esquema->codigo_producto,
                    'nombre_producto' => $p->esquema->nombre_producto,
                    'precio' => $p->precio,
                    'lote' => $p->lote->lote,
                    'oferta' => $p->oferta,
                    'stock' => $p->stock,
                ];
            });
        });

        $clientesConDescuento = $clientes->map(function ($cliente) {
            $cliente->descuento = TipoCliente::find($cliente->id_tipo_cliente)->descuento; 
            return $cliente;
        });

        $presentaciones = PresentacionVenta::where('estado', 'A')->get();

        $fecha_venta = DB::selectOne("SELECT GETDATE() AS fecha")->fecha;

        return view('ventas.registrarventas', compact('clientes', 'clientesConDescuento', 'tipo_venta', 'productos', 'productosAgrupados', 'tipo_pago', 'tipo_documento', 'presentaciones', 'fecha_venta' ));
    }

    public function crearVenta(Request $request)
    {
        $productos = $request->input('productos');  
        $productosTable = [];
        $subtotal = 0;

        foreach ($productos as $producto) {

            if (!isset($producto['precio_p']) || $producto['precio_p'] <= 0) {
                return response()->json(['error' => 'El precio es obligatorio y debe ser mayor a 0.'], 400);
            }

            if ($producto['cantidad'] <= 0) {
                return response()->json(['error' => 'La cantidad debe ser mayor a 0.'], 400);
            }

            $precioUnitario = floatval($producto['precio_p']);
            $cantidad = floatval($producto['cantidad']);

            $subtotal += $cantidad * $precioUnitario;

            $productosTable[] = [
                'id_producto' => $producto['id_producto'],
                'cantidad' => $cantidad,
                'precio' => $precioUnitario,
                'id_presentacion_venta' => $producto['id_presentacion_venta'],
            ];
        }

        $idCliente = $request->input('id_cliente');
        $cliente = Cliente::find($idCliente);
        $tipoCliente = TipoCliente::find($cliente->id_tipo_cliente);
        $descuento = $tipoCliente->descuento;

        $descuentoMonto = $subtotal * ($descuento / 100);
        $total = $subtotal - $descuentoMonto;

        $fechaDesdeBD = DB::selectOne("SELECT CONVERT(datetime, GETDATE()) AS fecha")->fecha;
        $fechaVenta = Carbon::parse($fechaDesdeBD);
        $idUsuario = Auth::id();

        $aperturaCaja = DB::table('Apertura_Caja')
            ->join('asignacion_sat', 'Apertura_Caja.ID_Asignacion', '=', 'asignacion_sat.id_asignacion')
            ->where('asignacion_sat.id_usuario', $idUsuario)
            ->where('Apertura_Caja.Estado', 'A')
            ->exists();

        if (!$aperturaCaja) {
            return redirect()->route('ventas.registrarventas')->with('error', 'El usuario no tiene una caja aperturada. No puede realizar la venta.');
        }
        $errorMessage = '';
        try{
            DB::transaction(function () use ($request, $productosTable, $subtotal, $descuentoMonto, $total, $idCliente, $fechaVenta, $idUsuario) {
            $idTipoVenta = $request->input('id_tipo_venta');
            $idTipoPago = $request->input('id_tipo_pago');
            $idTipoDocumento = $request->input('id_tipo_documento');
            $fechaVenta = Carbon::parse($fechaVenta)->toDateString();

            DB::statement("
                EXEC sp_movimiento_caja_venta
                    @id_usuario = ?, 
                    @monto = ?;
            ", [
                $idUsuario, 
                $total
                ]);

            DB::statement("
                DECLARE @Productos TipoDetalleVenta;

                INSERT INTO @Productos (id_producto, cantidad, precio, id_presentacion_venta)
                VALUES 
                " . implode(', ', array_map(function ($producto) {
                    return "({$producto['id_producto']}, {$producto['cantidad']}, {$producto['precio']}, {$producto['id_presentacion_venta']})";
                }, $productosTable)) . ";

                -- Llamar al procedimiento almacenado
                EXEC sp_RealizarVenta
                    @id_cliente = ?, 
                    @id_usuario = ?, 
                    @id_tipo_venta = ?, 
                    @id_tipo_pago = ?, 
                    @id_tipo_documento = ?, 
                    @fecha_venta = ?, 
                    @subtotal_venta = ?, 
                    @total_descuento = ?, 
                    @total_venta = ?, 
                    @productos = @Productos
            ", [
                $idCliente, 
                $idUsuario, 
                $idTipoVenta, 
                $idTipoPago, 
                $idTipoDocumento, 
                $fechaVenta, 
                $subtotal, 
                $descuentoMonto, 
                $total, 
                &$errorMessage
            ]);
            });
        } catch (\Exception $e) {
            return redirect()->route('ventas.registrarventas')->with('error', 'Error en la transacción: ' . $e->getMessage());
        }   

        $errorMessage = $result[0]->p_error_message ?? null;

        if ($errorMessage === 'La venta contiene un producto especial.') {
            return redirect()->route('ventas.registrar')->with('showModal', true);
        }

        if ($errorMessage !== 'Venta registrada correctamente.') {
            return redirect()->route('ventas.registrar')->with('error', $errorMessage);
        }

        return redirect()->route('ventas')->with('mensaje', 'Venta registrada exitosamente.');
    }

    public function anular($id)
    {
        DB::statement('EXEC sp_anularVenta ?', [$id]);

        return redirect()->route('ventas')->with('mensaje', 'Venta Anulada');
    }

    public function show($id)
    {
        $total_detalle = DB::table('vw_productos_vendidos')
            ->where('id_venta', $id)
            ->get();

            return view('ventas.partials.detalle_modal', compact('total_detalle'));
    }


    public function mostrarDetalle($id)
    {
        $total_detalle = DB::table('vw_productos_vendidos')
            ->where('id_venta', $id)
            ->get();

        return view('ventas.partials.detalle_modal', compact('total_detalle'));
    }
}

