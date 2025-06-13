<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OfertasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscador');
        $productos = DB::table('vw_Encabezado_oferta')
            ->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_oferta', 'LIKE', "%{$buscar}%")
                    ->orWhere('fecha', 'LIKE', "%{$buscar}%");
            })
            ->get();

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
        $request->validate([
            'nombre_oferta' => 'required|string|max:255',
            'codigo_oferta' => 'required|string|max:20',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|integer',
            'productos.*.id_lote' => 'required|integer',
            'productos.*.nombre_producto' => 'required|string|max:255',
            'productos.*.precio_regular' => 'required|numeric|min:0',
            'productos.*.porcentaje' => 'required|numeric|min:0|max:100',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.unidad' => 'required|integer|min:1',
            'productos.*.precio_oferta' => 'required|numeric|min:0',
        ]);

        Log::info('Datos recibidos:', $request->all()); // Guardar el log para depuración

        DB::beginTransaction();
        try {
            $sql = "DECLARE @Detallesxd DetalleOfertaType2; ";

            foreach ($request->input('productos') as $producto) {
                $sql .= "INSERT INTO @Detallesxd (id_producto, nombre_producto, precio_regular, porcentaje_oferta, lote, cantidadoferta, unidadoferta)
             VALUES ({$producto['id_producto']}, N'{$producto['nombre_producto']}', {$producto['precio_regular']}, {$producto['porcentaje']}, 
                     {$producto['id_lote']}, {$producto['cantidad']}, {$producto['unidad']}); ";
            }

            $sql .= "EXEC sp_InsertarOfertaConDetallesCompleto  
                     @NombreOferta = N'{$request->input('nombre_oferta')}',  
                     @FechaInicio = '{$request->input('fecha_inicio')}',  
                     @FechaFin = '{$request->input('fecha_fin')}',  
                     @codigo_oferta = '{$request->input('codigo_oferta')}',  
                     @id_empresa = 1,  
                     @Detallesxd = @Detallesxd;";

            DB::statement($sql);
            DB::commit();

            return response()->json(['mensaje' => 'Oferta y productos guardados correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al guardar la oferta: " . $e->getMessage());
            return response()->json(['error' => 'Error al guardar la oferta.', 'detalle' => $e->getMessage()], 500);
        }
    }

    public function buscarPorNombre(Request $request)
    {
        $nombre = $request->input('inputNombreProducto');
        $lotes = DB::table('vw_loteProducto')
            ->where('nombre_producto', 'LIKE', '%' . $nombre . '%')
            ->where('estado', 'A')
            ->where('stock', '>', 0)
            ->select('id_producto', 'id_lote', 'lote', 'stock', 'precio')
            ->get();

        if ($lotes->isEmpty()) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        return response()->json(['lotes' => $lotes]);
    }
    public function eliminarOferta($id)
    {
        DB::statement('EXEC sp_cambiar_estado_oferta ?', [$id]);

        return redirect()->route('ofertas')->with('mensaje', 'oferta eliminada exitosamente');
    }
}
