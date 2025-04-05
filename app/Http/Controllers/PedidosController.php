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
        $productos = json_decode($request->input('productos'), true);
        
        if (empty($productos)) {
            return response()->json(['error' => 'No se recibieron productos.'], 400);
        }
    
        DB::beginTransaction();
        try {
            // Crear la tabla temporal
            $detallePedidoTable = DB::table('DetallePedidoType');
    
            foreach ($productos as $producto) {
                $detallePedidoTable->insert([
                    'cantidad' => $producto['cantidad'],
                    'costo' => $producto['precio'],
                    'codigo_producto' => $producto['codigo'],
                    'id_pedido' => 0, // Se actualizará en el procedimiento
                    'estado' => 'A',
                ]);
            }
    
            // Llamar al procedimiento almacenado
            DB::statement("EXEC InsertarPedidoConDetalles ?", [$detallePedidoTable]);
    
            DB::commit();
            return response()->json(['mensaje' => 'Pedido y detalles guardados correctamente.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al guardar el pedido.'], 500);
        }
    
     }
 
     public function buscar(Request $request)
     {
         $request->validate([
             'codigo_producto' => 'required|string|max:15',
         ]);
 
         $producto = DB::table('esquema_producto')
             ->where('codigo_producto', $request->codigo_producto)
             ->select('nombre_producto')
             ->first();
 
         return response()->json(['nombre_producto' => $producto->nombre_producto ?? 'Producto no encontrado']);
     }
 }