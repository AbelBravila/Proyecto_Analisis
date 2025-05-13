<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Termwind\Components\Dd;

class OfertasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $productos = DB::table('vw_Encabezado_oferta') -> where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_oferta', 'LIKE', "%{$buscar}%")
                             ->orWhere('fecha', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('admin.ofertas', compact('productos', 'buscar'));
    }

    public function mostrarDetalles($id)
    {
        $detalles = DB::table('vw_Detalle_Ofertas')
            ->where('id_oferta', $id)
            ->get();    
        return view('layouts.partials.admin.detalleoferta', compact('detalles'));
    }

    public function create()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        return view('layouts.partials.admin.insertarofertamodal', compact('hoy'));
    }
    public function store(Request $request)
    {
        $productos = $request->input('productos');
        $nombreOferta = $request->input('nombre_oferta');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        if (empty($productos)) {
            return response()->json(['error' => 'No se recibieron productos.'], 400);
        }

        DB::beginTransaction();
        try {
            // Declarar la tabla tipo nueva
            $sql = "DECLARE @Detallesxd DetalleOfertaType2; ";

            // Insertar los productos (nombre_producto, precio_regular, porcentaje_oferta)
            foreach ($productos as $producto) {
                $nombreProducto = str_replace("'", "''", $producto['nombre']); // prevenir error de comillas
                $precio = floatval($producto['precio_regular']);
                $porcentaje = floatval($producto['porcentaje']);

                $sql .= "INSERT INTO @Detallesxd (nombre_producto, precio_regular, porcentaje_oferta)
                        VALUES (N'$nombreProducto', $precio, $porcentaje); ";
            }

            // Escapar nombre de la oferta
            $nombreOfertaEscapado = str_replace("'", "''", $nombreOferta);

            // Ejecutar el procedimiento
            $sql .= "EXEC sp_InsertarOfertaConDetalles2 
                        @NombreOferta = N'$nombreOfertaEscapado', 
                        @FechaInicio = '$fechaInicio', 
                        @FechaFin = '$fechaFin', 
                        @Detallesxd = @Detallesxd;";

            DB::statement($sql);
            DB::commit();

            return response()->json(['mensaje' => 'Oferta y detalles guardados correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar la oferta.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }
    
    public function buscarPorNombre(Request $request)
    {
        $nombre = $request->input('inputNombreProducto');
        $lotes = DB::table('vw_loteProducto')
        ->where('nombre_producto', 'LIKE', '%' . $nombre . '%')
        ->select('lote', 'stock', 'precio')
        ->get();
        if ($lotes->isEmpty()) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        return response()->json(['lotes' => $lotes]);
    }

    public function show($id)
    {
        return view('ofertas.show', compact('id'));
    }

    public function edit($id)
    {
        return view('ofertas.edit', compact('id'));
    }
}
