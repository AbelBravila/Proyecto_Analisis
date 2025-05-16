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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index_ventas(Request $request)
    {
        $buscar = $request->input('buscador');

        $ventas = DB::table('vw_detalle_venta') ->where('estado', '=', 'A') 
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_cliente', 'LIKE', "%{$buscar}%")
                            ->orWhere('id_venta', 'LIKE', "%{$buscar}%");
            })
            ->get();

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
                ];
            });
        });

        $clientesConDescuento = $clientes->map(function ($cliente) {
            $cliente->descuento = TipoCliente::find($cliente->id_tipo_cliente)->descuento; 
            return $cliente;
        });

        $presentaciones = PresentacionVenta::where('estado', 'A')->get();

        return view('ventas.registrarventas', compact('clientes', 'clientesConDescuento', 'tipo_venta', 'productos', 'productosAgrupados', 'tipo_pago', 'tipo_documento', 'presentaciones' ));
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
        
            // Calcular subtotal
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

        $fechaVenta = $request->input('fecha_venta');
        $hoy = Carbon::now()->format('Y-m-d');
        if ($fechaVenta > $hoy) {
            return back()->with('error', 'La fecha de venta no puede ser mayor a la fecha actual.');
        }

        $idUsuario = Auth::id();
        $aperturaCaja = DB::table('Apertura_Caja')
            ->join('asignacion_sat', 'Apertura_Caja.ID_Asignacion', '=', 'asignacion_sat.id_asignacion')
            ->where('asignacion_sat.id_usuario', $idUsuario)
            ->where('Apertura_Caja.Estado', 'A')
            ->exists();
        if (!$aperturaCaja) {
            return back()->with('error', 'El usuario no tiene una caja aperturada. No puede realizar la venta.');
        }


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
                    @productos = @Productos,
                    @p_error_message = ? OUTPUT;
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

            if ($errorMessage) {
                return back()->with('error', $errorMessage);
            }

        });

        return redirect()->route('ventas')->with('success', 'Venta registrada exitosamente.');
    }

    public function anular($id)
    {
        DB::statement('EXEC sp_anularVenta ?', [$id]);

        return redirect()->route('ventas')->with('success', 'Venta Anulada');
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

