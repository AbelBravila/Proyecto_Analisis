<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Turno;

class TurnoController extends Controller
{
    public function index_turno(Request $request)
    {
        $buscar = $request->input('buscador');

        $turnos = Turno::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_turno', 'LIKE', "%{$buscar}%")
                             ->orWhere('descripcion_turno', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación
            // dd($cajas);

        return view('cajas.turno', compact('turnos', 'buscar'));
    }

    private function validateCaja(Request $request)
    {
        $request->validate([
            'descripcion_turno' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateCaja($request);

        DB::statement("EXEC sp_Insert_Turno
            @descripcion_turno = ?",
            [$request->descripcion_turno]
        );

        return redirect()->route('turnos')->with('success', 'Turno registrado exitosamente');
    }

    public function editar_turno($id)
    {
        $turnos = Turnos::findOrFail($id);

        return view('cajas.turno', compact('turnos'));
    }

    public function actualizar_turnos(Request $request, $id)
    {
        // $this->validateProveedor($request, [
        //     'nit' => [
        //         'required', 
        //         'string', 
        //         'max:15', 
        //         Rule::unique('nit')->ignore($id, 'id_proveedor') // Ignora el producto actual
        //     ],
        // ]);
    
        // Procedimiento almacenado para actualizar el producto
        DB::statement('EXEC sp_actualizar_turno
            @id_turno = ?,
            @descripcion_turno = ?',
            [
                $id, 
                $request->descripcion_turno
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('turnos')->with('success', 'Turno actualizado exitosamente.');
        
    }


    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoTurno ?', [$id]);

        return redirect()->route('turnos')->with('success', 'Estado del turno actualizado');
    }
}
