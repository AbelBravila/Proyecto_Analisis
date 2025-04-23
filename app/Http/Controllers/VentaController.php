<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\TipoVenta;
use App\Models\TipoPago;
use App\Models\TipoDocumento;
use App\Models\EsquemaProducto;
use App\Models\TipoCliente;
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
            'presentacion',    
            'lote',            
        ])->where('estado', 'A')->get();

         // Mapear el descuento de cada cliente según el tipo de cliente
        $clientesConDescuento = $clientes->map(function ($cliente) {
            $cliente->descuento = TipoCliente::find($cliente->id_tipo_cliente)->descuento; 
            return $cliente;
        });

        // Enviar valores para el cálculo de los totales a la vista
        $descuentoTotal = 0;
        $totalConDescuento = 0;

        // Esto es solo si deseas calcular un descuento predeterminado para el primer cliente, si no solo hazlo en el frontend
        if (isset($clientesConDescuento[0])) {
            $descuento = $clientesConDescuento[0]->descuento; // Solo ejemplo con el primer cliente
            $descuentoTotal = ($productos->sum('precio') * ($descuento / 100));  // Aquí estás calculando el descuento de los productos
            $totalConDescuento = $productos->sum('precio') - $descuentoTotal;
        }

        return view('ventas.registrarventas', compact('clientes', 'clientesConDescuento', 'tipo_venta', 'productos', 'tipo_pago', 'tipo_documento',  'descuentoTotal',  'totalConDescuento' ));
    }

    // Crear una venta
    public function crearVenta(Request $request)
    {
        $productos = $request->input('productos');  // Un array de productos con sus cantidades y precios
        $productosTable = [];
        $subtotal = 0;

        foreach ($productos as $producto) {
            // Validar la cantidad y el precio
            if ($producto['cantidad'] <= 0 || $producto['precio'] <= 0) {
                return response()->json(['error' => 'La cantidad y el precio deben ser mayores a 0.'], 400);
            }

            // Calcular el subtotal (producto * cantidad)
            $subtotal += $producto['cantidad'] * $producto['precio'];

            $productosTable[] = [
                'id_producto' => $producto['id_producto'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'],
            ];
        }

        // Obtener el cliente seleccionado y su tipo de cliente
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

        // Preparar los valores para pasar al procedimiento almacenado
        DB::transaction(function () use ($request, $productosTable, $subtotal, $descuentoMonto, $total, $idCliente, $fechaVenta) {
            $insertQuery = [];
            foreach ($productosTable as $producto) {
                $insertQuery[] = sprintf(
                    "(%d, %d, %.2f)",  // Formato para el procedimiento
                    $producto['id_producto'],
                    $producto['cantidad'],
                    $producto['precio']
                );
            }
            $insertValues = implode(', ', $insertQuery);
            
            $idUsuario = Auth::id();
            $idTipoVenta = $request->input('id_tipo_venta');
            $idTipoPago = $request->input('id_tipo_pago');
            $idTipoDocumento = $request->input('id_tipo_documento');
            $fechaVenta = Carbon::parse($fechaVenta)->toDateString();

            // Ejecutar el procedimiento almacenado con los datos de la venta
            DB::statement("
                DECLARE @Productos TipoDetalleVenta;
                
                -- Insertar los productos en la variable tipo de tabla
                INSERT INTO @Productos (id_producto, cantidad, precio)
                VALUES $insertValues;

                -- Llamar al procedimiento almacenado
                EXEC sp_RealizarVenta
                    @id_cliente = ?, 
                    @id_usuario = ?, 
                    @id_tipo_venta = ?, 
                    @id_tipo_pago = ?, 
                    @id_tipo_documento = ?, 
                    @fecha_venta = ?, 
                    @productos = @Productos
            ", [
                $idCliente, 
                $idUsuario, 
                $idTipoVenta, 
                $idTipoPago, 
                $idTipoDocumento, 
                $fechaVenta, 
            ]);
        });

        return redirect()->route('ventas.ventas')->with('success', 'Venta registrada exitosamente.');
    }


}
