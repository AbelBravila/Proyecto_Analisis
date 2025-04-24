<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Pasillo;
use App\Models\Estanteria;



class EstanteController extends Controller
{

    public function index()
    {
        $estantes = DB::table('estanteria')->where('estado', 'A')->get();
        return view('Ubicaciones.estante', compact('estantes'));
    }

    public function index_estante(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $estantes = DB::table('vw_estanteria_pasillo')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('codigo_estanteria', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

            $pasillos = Pasillo::where('estado', 'A')->get();

        return view('Ubicaciones.estante', compact('estantes', 'buscar', 'pasillos'));


    }

    private function validateEstante(Request $request)
    {
        $request->validate([
            'codigo_estanteria' => 'required|string',
        ]);
    }


    public function ingreso_Estante(Request $request)
    {
        $this->validateEstante($request);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarEstanteria 
            @cod_estanteria = ?,
            @pasillo = ?",
            [$request->codigo_estanteria, $request->pasillo]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Estanteria')->with('success', 'Estanteria registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoEstante ?', [$id]);

        return redirect()->route('Estanteria')->with('success', 'Estado del estaria actualizado');
    }

    public function editar_estante($id)
    {
        $estanteria = Estanteria::findOrFail($id);

        return view('Ubicaciones.estante', compact('estanteria'));

    }

    public function actualizar_estante(Request $request, $id)
    {
        $this->validateEstante($request, [
            'codigo_estanteria' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('estanteria')->ignore($id, 'id_estanteria') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_estanteria
            @id_estanteria = ?, 
            @codigo_estanteria = ?, 
            @id_pasillo = ?',
            [
                $id, 
                $request->codigo_estanteria, 
                $request->pasillo
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Estanteria')->with('success', 'Estanteria actualizado exitosamente.');
        
    }
    
    public function mostrarFormulario($idEstanteria = null)
    {
        
        $estanteria = Estanteria::find($idEstanteria);
        $selectedPasillo = $estanteria?->id_pasillo; // esto debe ser un INT

        return view('Ubicaciones.estante', compact('pasillos', 'selectedPasillo'));
    }

}