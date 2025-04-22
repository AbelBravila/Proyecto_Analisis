<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Proveedor;
use App\Models\EsquemaProducto;
use App\Models\Producto;
use App\Models\TipoCompra;
use App\Models\Presentacion;
use App\Models\Estanteria;

class ComprasController extends Controller
{
    public function index_compras(Request $request)
    {
        $buscar = $request->input('buscador');

        $compras = DB::table('vw_detalle_compra') ->where('estado', '=', 'A') 
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_proveedor', 'LIKE', "%{$buscar}%")
                            ->orWhere('id_compra', 'LIKE', "%{$buscar}%");
            })
            ->get();

        return view('compras.compras', compact('compras'));
    }

    public function index_resgistrar()
    {
        $proveedores = Proveedor::where('estado', 'A')->get();
        $tipo_compra = TipoCompra::where('estado', 'A')->get();
        $productos = EsquemaProducto::where('estado', 'A')->get(); 
        $presentaciones = Presentacion::where('estado', 'A')->get();
        $estanterias = Estanteria::where('estado', 'A')->get();
        return view('compras.registrarcompras', compact('productos', 'presentaciones', 'tipo_compra', 'proveedores', 'estanterias'));
        
    }

    public function crearCompra(Request $request)
    {
        // Recoger los productos y otros datos de la solicitud
        $productos = $request->input('productos');  // Un array de productos
        $estanteria = $request->input('estanteria'); // Recoger el dato de la estantería
    
        // Crear una tabla temporal de productos en SQL Server
        $productosTable = [];
        foreach ($productos as $producto) {
            // Convertir las fechas con Carbon y asegurarse de que estén en el formato correcto
            $fechaFabricacion = Carbon::parse($producto['fecha_fabricacion'])->format('Ymd');
            $fechaVencimiento = Carbon::parse($producto['fecha_vencimiento'])->format('Ymd');           

            // Log para verificar las fechas
            \Log::info("Producto ID: {$producto['id_esquema_producto']} - Fecha Fabricación: {$fechaFabricacion} - Fecha Vencimiento: {$fechaVencimiento}");
    
            // Validar que las fechas no sean nulas o vacías
            if (empty($fechaFabricacion) || empty($fechaVencimiento)) {
                return response()->json(['error' => 'Las fechas de fabricación y vencimiento no pueden estar vacías.'], 400);
            }
    
            // Asegurarse de que las fechas estén dentro del rango válido de SQL Server
            if (!$this->isValidDate($fechaFabricacion) || !$this->isValidDate($fechaVencimiento)) {
                return response()->json(['error' => 'Fecha fuera de rango o formato inválido.'], 400);
            }
    
            // Agregar el producto a la tabla temporal
            $productosTable[] = [
                'IdEsquemaProducto' => $producto['id_esquema_producto'],
                'Lote' => $producto['lote'],
                'Fabricante' => $producto['fabricante'],
                'FechaFabricacion' => $fechaFabricacion,
                'FechaVencimiento' => $fechaVencimiento,
                'IdPresentacion' => $producto['id_presentacion'],
                'Cantidad' => $producto['cantidad'],
                'Costo' => $producto['costo'],
                'Estanteria' => $producto['id_estanteria'] 
            ];
        }
    
        // Log para verificar la consulta generada
        $insertValues = [];
        foreach ($productosTable as $producto) {
            $insertValues[] = sprintf(
                "(%d, '%s', '%s', '%s', '%s', %d, %d, %.2f)",
                $producto['IdEsquemaProducto'],
                $producto['Lote'],
                $producto['Fabricante'],
                $producto['FechaFabricacion'],
                $producto['FechaVencimiento'],
                $producto['IdPresentacion'],
                $producto['Cantidad'],
                $producto['Costo'],
                $producto['Estanteria']
            );     
            
        }
        $insertQuery = implode(', ', $insertValues);
        \Log::info("Insert Query: " . $insertQuery);
        // Obtener la empresa del usuario actual
        $idEmpresa = Auth::user()->empresa()->where('estado', 'A')->first()?->id_empresa;

        if (!$idEmpresa) {
            return back()->with('error', 'No se encontró una empresa asociada al usuario.');
        }
        
        $fechaCompra = $request->input('fecha_compra');
        $hoy = Carbon::now()->format('Y-m-d');

        if ($fechaCompra > $hoy) {
            return back()->with('error', 'La fecha de compra no puede ser mayor a la fecha actual.');
        }

        // Ejecutar el procedimiento almacenado usando una tabla temporal
        DB::transaction(function () use ($request, $insertQuery, $idEmpresa) {
            DB::statement("
            DECLARE @Productos TipoProductos;

            INSERT INTO @Productos (IdEsquemaProducto, Lote, Fabricante, FechaFabricacion, FechaVencimiento, IdPresentacion, Cantidad, Costo)
            VALUES $insertQuery;

            EXEC sp_RegistrarCompra 
                @FechaCompra = ?, 
                @IdTipoCompra = ?, 
                @IdProveedor = ?, 
                @Productos = @Productos, 
                @IdEmpresa = ?;
            ", [
                Carbon::parse($request->input('fecha_compra'))->toDateString(), 
                $request->input('id_tipo_compra'),
                $request->input('id_proveedor'),
                $idEmpresa
            ]);

        });
        
    
        // Redirigir con un mensaje de éxito
        return redirect()->route('compras')->with('success', 'Compra registrada exitosamente.');
    }

    public function anular($id)
    {
        DB::statement('EXEC sp_anularCompra ?', [$id]);

        return redirect()->route('compras')->with('success', 'Compra Anulada');
    }
    

    // Función para verificar si la fecha está en un rango válido para SQL Server
    private function isValidDate($date)
    {
        try {
            $parsedDate = Carbon::parse($date);
            // Verificar si la fecha está en el rango válido de SQL Server
            return $parsedDate->between(Carbon::createFromDate(1753, 1, 1), Carbon::createFromDate(9999, 12, 31));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function show($id)
    {
        $total_detalle = DB::table('vw_productos_comprados')
            ->where('id_compra', $id)
            ->get();

            return view('compras.partials.detalle_modal', compact('total_detalle'));
    }


    public function mostrarDetalle($id)
    {
        $total_detalle = DB::table('vw_productos_comprados')
            ->where('id_compra', $id)
            ->get();

        return view('compras.partials.detalle_modal', compact('total_detalle'));
    }
}
