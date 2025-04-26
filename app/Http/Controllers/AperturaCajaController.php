<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AperturaCaja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Turno;
use Carbon\Carbon;

class AperturaCajaController extends Controller
{

     
     public function index(Request $request)
     {
         $busqueda = $request->input('buscador');
 
         
         $aperturas = DB::table('vista_apertura_caja')
         ->when($busqueda, function ($query, $busqueda) {
             return $query->where('nombre_usuario', 'like', "%$busqueda%")
                          ->orWhere('fechaHoraApertura', 'like', "%$busqueda%")
                          ->orWhere('nombre_caja', 'like', "%$busqueda%")
                          ->orWhere('descripcion_turno', 'like', "%$busqueda%");
         })
         ->get();

        $usuarios=DB::select("SELECT id_usuario, nombre_usuario FROM usuario where estado = 'A'");  

        $turnos=DB::select("SELECT * FROM turno where estado = 'A'");
 
         return view('cajas.aperturacaja', compact('aperturas', 'usuarios', 'turnos'));
     }

     public function cajasPorUsuario($id)
    {   
    $cajas = DB::table('asignacion_sat')
        ->join('caja', 'asignacion_sat.id_caja', '=', 'caja.id_caja')
        ->where('asignacion_sat.id_usuario', $id)
        ->where('asignacion_sat.estado', 'A')
        ->select('caja.id_caja', 'caja.nombre_caja', 'asignacion_sat.id_asignacion')
        ->distinct()
        ->get();

    return response()->json($cajas);
    }

    public function store(Request $request)
{
    $request->validate([
        'id_asignacion' => 'required|integer',
        'id_turno' => 'required|integer',
        'monto_inicial' => 'required|numeric|min:0'
    ]);

    $asignacion = DB::table('asignacion_sat')
                    ->where('id_asignacion', $request->id_asignacion)
                    ->first();

    if (!$asignacion) {
        return redirect()->back()->with('error', 'Asignación no encontrada.');
    }

    $yaEstaAbierta = DB::table('Apertura_Caja')
                        ->where('ID_Asignacion', $request->id_asignacion)
                        ->where('estado', 'A')
                        ->exists();

    if ($yaEstaAbierta) {
        return redirect()->back()->with('error', 'Esta asignación ya tiene una caja abierta.');
    }

    $usuarioTieneCajaAbierta = DB::table('Apertura_Caja as ac')
        ->join('asignacion_sat as a', 'ac.ID_Asignacion', '=', 'a.id_asignacion')
        ->where('a.id_usuario', $asignacion->id_usuario)
        ->where('ac.estado', 'A')
        ->where('ac.ID_Asignacion', '!=', $request->id_asignacion)
        ->exists();

    if ($usuarioTieneCajaAbierta) {
        return redirect()->back()->with('error', 'Este usuario ya tiene una caja aperturada.');
    }

    $cajaYaEstaAbierta = DB::table('Apertura_Caja as ac')
        ->join('asignacion_sat as a', 'ac.ID_Asignacion', '=', 'a.id_asignacion')
        ->where('a.id_caja', $asignacion->id_caja)
        ->where('ac.estado', 'A')
        ->exists();

    if ($cajaYaEstaAbierta) {
        return redirect()->back()->with('error', 'Esta caja ya está aperturada.');
    }

    try {
        DB::table('Apertura_Caja')->insert([
            'ID_Asignacion' => $request->id_asignacion,
            'ID_Turno' => $request->id_turno,
            'estado' => 'A',
            'FechaHoraApertura' => now(),
            'MontoInicial' => $request->monto_inicial,
        ]);

        return redirect()->route('apertura-caja.index')->with('success', 'Caja aperturada correctamente.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al aperturar la caja: ' . $e->getMessage());
    }
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



public function cerrarCaja($id)
{
    DB::beginTransaction();

    try {
        $apertura = DB::table('Apertura_Caja')->where('ID_Apertura', $id)->first();

        if (!$apertura) {
            return redirect()->back()->with('error', 'Apertura no encontrada.');
        }

        $ingresos = DB::table('movimientos_caja')
            ->where('id_apertura', $id)
            ->where('tipo', 'ingreso')
            ->sum('monto');
        
        $egresos = DB::table('movimientos_caja')
            ->where('id_apertura', $id)
            ->where('tipo', 'egreso')
            ->sum('monto');

        $montoFinal = $apertura->MontoInicial + $ingresos - $egresos;

        DB::table('Cierre_Caja')->insert([
            'ID_Apertura' => $apertura->ID_Apertura,
            'FechaHoraCierre' => Carbon::now(),
            'MontoFinal' => $montoFinal
        ]);

        DB::table('Apertura_Caja')->where('ID_Apertura', $id)->update([
            'estado' => 'C',
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Caja cerrada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un error al cerrar la caja: ' . $e->getMessage());
    }
}
}