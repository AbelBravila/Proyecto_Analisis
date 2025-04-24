<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CierreCajaController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('buscador');

        
        $aperturas = DB::table('vista_cierre_caja')
        ->when($busqueda, function ($query, $busqueda) {
            return $query->where('nombre_usuario', 'like', "%$busqueda%")
                         ->orWhere('nombre_caja', 'like', "%$busqueda%")
                         ->orWhere('descripcion_turno', 'like', "%$busqueda%");
        })
        ->get();

        return view('cajas.cierrecaja', compact('aperturas'));
    }

    public function movimientosJson($id)
{
    $apertura = DB::table('Apertura_Caja')->where('ID_Apertura', $id)->first();

    if (!$apertura) {
        return response()->json(['error' => 'No se encontró la apertura.'], 404);
    }

    $movimientos = DB::table('movimientos_caja')
                    ->where('id_apertura', $id)
                    ->orderBy('fecha', 'asc')
                    ->get();

    $resumen = DB::table('movimientos_caja')
        ->where('id_apertura', $id)
        ->selectRaw("
            SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as total_ingresos,
            SUM(CASE WHEN tipo = 'egreso' THEN monto ELSE 0 END) as total_egresos
        ")
        ->first();

    $saldo_final = $apertura->MontoInicial + $resumen->total_ingresos - $resumen->total_egresos;

    return response()->json([
        'apertura' => $apertura,
        'movimientos' => $movimientos,
        'resumen' => $resumen,
        'saldo_final' => $saldo_final
    ]);
}
}
