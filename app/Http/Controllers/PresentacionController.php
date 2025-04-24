<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Presentacion;


class PresentacionController extends Controller
{

    public function index()
    {
        $presentaciones = DB::table('presentacion')->where('estado', 'A')->get();
        return view('Tipo.presentacion', compact('presentaciones'));
    }
    
    public function index_presentacion(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $presentaciones = DB::table('presentacion')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('presentacion', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Tipo.presentacion', compact('presentaciones', 'buscar'));
    }

    private function validatePresentacion(Request $request)
    {
        $request->validate([
            'presentacion' => 'required|string',
        ]);
    }

    public function ingreso_presentacion(Request $request)
    {
        $this->validatePresentacion($request);
    
  
        DB::statement("EXEC sp_InsertarPresentacion 
            @presentacion = ?",
            [$request->presentacion]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Presentacion')->with('success', 'Tipo de presentacion registrada exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoPresentacion ?', [$id]);

        return redirect()->route('Presentacion')->with('success', 'Estado del tipo de presentacion actualizado');
    }

    public function editar_presentacion($id)
    {
        $presentacion = Presentacion::findOrFail($id);

        return view('Tipo.presentacion', compact('presentacion'));
    }

    public function actualizar_presentacion(Request $request, $id)
    {
        $this->validatePresentacion($request, [
            'presentacion' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('presentacion')->ignore($id, 'id_presentacion') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_presentacion
            @id_presentacion= ?, 
            @presentacion = ?',
            [
                $id, 
                $request->presentacion, 
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Presentacion')->with('success', 'Tipo de presentacion actualizada exitosamente.');
        
    }
}