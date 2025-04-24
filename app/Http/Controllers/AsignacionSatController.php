<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\asignacion_sat;
use Illuminate\Http\Request;

class AsignacionSatController extends Controller
{
    public function index(Request $request)
     {
         $busqueda = $request->input('buscador');
 
         $asignaciones = DB::table('vw_asignacion_caja')
         ->when($busqueda, function ($query, $busqueda) {
             return $query->where('nombre_usuario', 'like', "%$busqueda%")
                          ->orWhere('nombre_caja', 'like', "%$busqueda%");
         })
         ->get();
             
             $cajas=DB::select("SELECT id_caja, nombre_caja FROM caja where estado = 'A'");
             $usuarios=DB::select("SELECT id_usuario, nombre_usuario FROM usuario where estado = 'A'");
      
              return view('cajas.asignacioncaja', compact('asignaciones', 'cajas', 'usuarios'));
     }


     public function store(Request $request)
    {
        $request->validate([
            'id_caja' => 'required|integer',
            'id_usuario' => 'required|integer',
        ]);

    
        $existe = DB::table('asignacion_sat')
                ->where('id_caja', $request->id_caja)
                ->where('id_usuario', $request->id_usuario)
                ->where('estado', 'A') 
                ->exists();

        if ($existe) {
            return redirect()->back()->with('error', 'Esta caja ya está asignada a este usuario.');
        }   

    DB::beginTransaction();

    try {
        DB::table('asignacion_sat')->insert([
            'id_caja' => $request->id_caja,
            'id_usuario' => $request->id_usuario,
            'estado' => 'A',
            'fecha_asignacion' => now()
        ]);

        DB::commit();

        return redirect()->route('asignacion-caja.index')
                         ->with('success', 'Caja asignada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al guardar: ' . $e->getMessage());
    }
}


public function update(Request $request, string $id)
{
    $request->validate([
        'id_caja' => 'required|integer',
        'id_usuario' => 'required|integer'
    ]);

    $asignacion = asignacion_sat::findOrFail($id);

    $existe = DB::table('asignacion_sat')
                ->where('id_caja', $request->id_caja)
                ->where('id_usuario', $request->id_usuario)
                ->where('estado', 'A')
                ->where('id_asignacion', '!=', $id)
                ->exists();

    if ($existe) {
        return redirect()->back()->with('error', 'Esta combinación de caja y usuario ya está asignada activamente.');
    }

    $asignacion->update([
        'id_caja' => $request->id_caja,
        'id_usuario' => $request->id_usuario
    ]);

    return redirect()->route('asignacion-caja.index')
                     ->with('success', 'Asignación actualizada correctamente.');
}

    public function destroy(string $id)
    {
        $asignacion = asignacion_sat::findOrFail($id);

        // dd($asignacion);
        $asignacion->update([
            'estado' => "I",
            ]);

            
        return redirect()->route('asignacion-caja.index');
    }
}
