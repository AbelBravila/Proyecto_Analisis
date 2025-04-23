<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cajas;


class CajasController extends Controller
{
    public function index_cajas(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $cajas = Cajas::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_caja', 'LIKE', "%{$buscar}%")
                             ->orWhere('nombre_caja', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación
            // dd($cajas);

        return view('cajas.cajas', compact('cajas', 'buscar'));
    }

    private function validateCaja(Request $request)
    {
        $request->validate([
            'nombre_caja' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateCaja($request);

        DB::statement("EXEC sp_Insert_caja
            @nombre_caja = ?",
            [$request->nombre_caja]
        );

        return redirect()->route('cajas')->with('success', 'Caja registrada exitosamente');
    }

    public function editar_caja($id)
    {
        $cajas = Cajas::findOrFail($id);

        return view('cajas.cajas', compact('cajas'));
    }

    public function actualizar_cajas(Request $request, $id)
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
        DB::statement('EXEC sp_actualizar_caja
            @id_caja = ?,
            @nombre_caja = ?',
            [
                $id, 
                $request->nombre_caja
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('cajas')->with('success', 'Caja actualizada exitosamente.');
        
    }


    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoCaja ?', [$id]);

        return redirect()->route('cajas')->with('success', 'Estado de la caja actualizado');
    }
}
