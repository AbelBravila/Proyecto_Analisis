<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use App\Models\EsquemaProducto;
use App\Models\Producto;
use App\Models\TipoCompra;

class ComprasController extends Controller
{
    public function index_compras(Request $request)
    {
        $buscar = $request->input('buscador');

        $compras = DB::table('vw_detalle_compra')
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
        return view('compras.registrarcompras', compact('proveedores', 'tipo_compra'));
    }
    
}