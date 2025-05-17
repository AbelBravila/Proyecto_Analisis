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
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $compras = DB::table('vw_detalle_compra')
            ->where('estado', '!=', 'I')
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('nombre_proveedor', 'LIKE', "%{$buscar}%")
                    ->orWhere('id_compra', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($fechaInicio, function ($query, $fechaInicio) {
                return $query->whereDate('fecha_compra', '>=', $fechaInicio);
            })
            ->when($fechaFin, function ($query, $fechaFin) {
                return $query->whereDate('fecha_compra', '<=', $fechaFin);
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

        $productos = $request->input('productos');  
        $productosTable = [];
        foreach ($productos as $producto) {
            $fechaFabricacion = Carbon::parse($producto['fecha_fabricacion'])->format('Ymd');
            $fechaVencimiento = Carbon::parse($producto['fecha_vencimiento'])->format('Ymd');           
    
            if (empty($fechaFabricacion) || empty($fechaVencimiento)) {
                return response()->json(['error' => 'Las fechas de fabricación y vencimiento no pueden estar vacías.'], 400);
            }

            if (!$this->isValidDate($fechaFabricacion) || !$this->isValidDate($fechaVencimiento)) {
                return response()->json(['error' => 'Fecha fuera de rango o formato inválido.'], 400);
            }

            $productosTable[] = [
                'IdEsquemaProducto' => $producto['id_esquema_producto'],
                'Lote' => $producto['lote'],
                'Fabricante' => $producto['fabricante'],
                'FechaFabricacion' => $fechaFabricacion,
                'FechaVencimiento' => $fechaVencimiento,
                'IdPresentacion' => $producto['id_presentacion'],
                'Cantidad' => $producto['cantidad'],
                'Costo' => $producto['costo'],
                'IdEstanteria' => $producto['id_estanteria'] 
            ];
        }
    
        $insertValues = [];
        foreach ($productosTable as $producto) {
            $insertValues[] = sprintf(
                "(%d, '%s', '%s', '%s', '%s', %d, %d, %.2f, %d)",
                $producto['IdEsquemaProducto'],
                $producto['Lote'],
                $producto['Fabricante'],
                $producto['FechaFabricacion'],
                $producto['FechaVencimiento'],
                $producto['IdPresentacion'],
                $producto['Cantidad'],
                $producto['Costo'],
                $producto['IdEstanteria']
            );     
            
        }
        $insertQuery = implode(', ', $insertValues);
        $idEmpresa = Auth::user()->empresa()->where('estado', 'A')->first()?->id_empresa;

        if (!$idEmpresa) {
            return back()->with('error', 'No se encontró una empresa asociada al usuario.');
        }
        
        $fechaCompra = $request->input('fecha_compra');
        $hoy = Carbon::now()->format('Y-m-d');

        if ($fechaCompra > $hoy) {
            return back()->with('error', 'La fecha de compra no puede ser mayor a la fecha actual.');
        }

        DB::transaction(function () use ($request, $insertQuery, $idEmpresa) {
            DB::statement("
            DECLARE @Productos TipoProductos;

            INSERT INTO @Productos (IdEsquemaProducto, Lote, Fabricante, FechaFabricacion, FechaVencimiento, IdPresentacion, Cantidad, Costo, IdEstanteria)
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
        

        return redirect()->route('compras')->with('success', 'Compra registrada exitosamente.');
    }

    public function anular($id)
    {
        DB::statement('EXEC sp_anularCompra ?', [$id]);

        return redirect()->route('compras')->with('success', 'Compra Anulada');
    }
    

    private function isValidDate($date)
    {
        try {
            $parsedDate = Carbon::parse($date);
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
