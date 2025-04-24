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
    // Mostrar el formulario para registrar una venta
    public function index_registrar()
    {
        $clientes = Cliente::where('estado', 'A')->get();
        $tipo_venta = TipoVenta::where('estado', 'A')->get();
        $tipo_pago = TipoPago::where('estado', 'A')->get();
        $tipo_documento = TipoDocumento::where('estado', 'A')->get();
        $productos = Producto::with([
            'esquema',  
            'lote',            
        ])->where('estado', 'A')->get();

        // Mapear el descuento de cada cliente según el tipo de cliente
        $clientesConDescuento = $clientes->map(function ($cliente) {
            $cliente->descuento = TipoCliente::find($cliente->id_tipo_cliente)->descuento; 
            return $cliente;
        });

        $presentaciones = PresentacionVenta::where('estado', 'A')->get();


        return view('ventas.registrarventas', compact('clientes', 'clientesConDescuento', 'tipo_venta', 'productos', 'tipo_pago', 'tipo_documento', 'presentaciones' ));
    }

    public function crearVenta(Request $request)
    {
        $productos = $request->input('productos');  // Un array de productos con sus cantidades, precios, y presentaciones
        $productosTable = [];
        $subtotal = 0;

        foreach ($productos as $producto) {
            // Verificar si el precio es mayor a 0
            if (!isset($producto['precio_p']) || $producto['precio_p'] <= 0) {
                // Log para ver el valor de 'precio' que está llegando
        
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
                'precio' => $precioUnitario, // ESTE es el precio final que el usuario ve
                'id_presentacion_venta' => $producto['id_presentacion_venta'],
            ];
        }
        

        $idCliente = $request->input('id_cliente');
        $cliente = Cliente::find($idCliente);
        $tipoCliente = TipoCliente::find($cliente->id_tipo_cliente);
        $descuento = $tipoCliente->descuento;

        // Calcular el monto del descuento
        $descuentoMonto = $subtotal * ($descuento / 100);

        // Calcular el total después de aplicar el descuento
        $total = $subtotal - $descuentoMonto;

        // Validar la fecha de venta
        $fechaVenta = $request->input('fecha_venta');
        $hoy = Carbon::now()->format('Y-m-d');
        if ($fechaVenta > $hoy) {
            return back()->with('error', 'La fecha de venta no puede ser mayor a la fecha actual.');
        }

        // Preparar los valores para el procedimiento almacenado
        DB::transaction(function () use ($request, $productosTable, $subtotal, $descuentoMonto, $total, $idCliente, $fechaVenta) {
            $idUsuario = Auth::id();
            $idTipoVenta = $request->input('id_tipo_venta');
            $idTipoPago = $request->input('id_tipo_pago');
            $idTipoDocumento = $request->input('id_tipo_documento');
            $fechaVenta = Carbon::parse($fechaVenta)->toDateString();

            // Ejecutar el procedimiento almacenado con los datos de la venta
            DB::statement("

                -- Declarar la variable de tipo tabla
                DECLARE @Productos TipoDetalleVenta;

                -- Insertar los productos en la variable tipo de tabla
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


}

