<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index_pedidos()
    {
        return view('admin.pedidos');
    }

    public function store(Request $request)
    {
        $productos = $request->input('productos');

        if (empty($productos)) {
            return response()->json(['error' => 'No se recibieron productos.'], 400);
        }

        DB::beginTransaction();
        try {
            // Crear una variable de tabla en SQL Server para almacenar los detalles del pedido temporalmente
            $tablaDetalles = collect($productos)->map(function ($producto) {
                return [
                    'cantidad' => $producto['cantidad'],
                    'costo' => $producto['precio'],
                    'codigo_producto' => $producto['codigo'],
                    'id_pedido' => null,  // Se asignará después en el procedimiento
                    'estado' => 'A',
                ];
            });

            // Insertar los datos en una tabla temporal en SQL Server
            $sql = "DECLARE @DetallesPedido DetallePedidoType; ";
            foreach ($tablaDetalles as $detalle) {
                $sql .= "INSERT INTO @DetallesPedido (cantidad, costo, codigo_producto, id_pedido, estado) VALUES ("
                    . $detalle['cantidad'] . ", "
                    . $detalle['costo'] . ", '"
                    . $detalle['codigo_producto'] . "', "
                    . ($detalle['id_pedido'] === null ? 'NULL' : $detalle['id_pedido']) . ", '"
                    . $detalle['estado'] . "'); ";
            }

            // Ejecutar el procedimiento almacenado con la tabla de tipo
            $sql .= "EXEC sp_InsertarPedidoConDetalles @DetallesPedido;";
            DB::statement($sql);

            DB::commit();
            return response()->json(['mensaje' => 'Pedido y detalles guardados correctamente.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al guardar el pedido.', 'detalle' => $e->getMessage()], 500);
        }
    }


    public function VerPedido()
    {
        $pedidos = DB::table('pedido')
            ->where('estado', 'A')
            ->get();

        return view('admin.VerPedidos', compact('pedidos'));
    }
    public function mostrarDetalles($id)
    {
        $detalles = DB::table('VW_Detalle_Pedidos')
            ->where('id_pedido', $id)
            ->get();

        return view('layouts.partials.admin.detallepedidos', compact('detalles'));
    }

    public function eliminarPedido($id)
    {
        DB::statement('EXEC sp_cambiar_estado_pedido ?', [$id]);

        return redirect()->route('pedidos.realizados')->with('mensaje', 'Pedido eliminado exitosamente');
    }
    
}
