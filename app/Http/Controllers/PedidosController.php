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
      dd($request->all());

         if (!$request->has(['cantidad', 'precio_unitario', 'codigo_producto'])) {
             return redirect()->back()->with('error', 'Debe agregar al menos un producto.');
         }
 
         $detallesXml = '<Detalles>';

         foreach ($request->cantidad as $index => $cantidad) {
             $precioUnitario = floatval($request->precio_unitario[$index] ?? 0);
             $cantidad = intval($cantidad ?? 0);
             $costo = $cantidad * $precioUnitario;
             $idProducto = intval($request->codigo_producto[$index] ?? 0);
 
             $detallesXml .= "<Detalle>
                                 <cantidad>{$cantidad}</cantidad>
                                 <costo>{$costo}</costo>
                                 <id_producto>{$idProducto}</id_producto>
                                 <estado>A</estado>
                             </Detalle>";
         }
 
         $detallesXml .= '</Detalles>';
   
 
         try {
             DB::statement('EXEC InsertarPedido ?, ?, ?', [now(), 'A', $detallesXml]);
             return redirect()->back()->with('success', 'Pedido guardado correctamente.');
         } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Error al guardar el pedido: ' . $e->getMessage());
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