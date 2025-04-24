<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Pasillo;


class PasilloController extends Controller
{

    public function index()
    {
        $pasillos = DB::table('pasillo')->where('estado', 'A')->get();
        return view('Ubicaciones.pasillo', compact('pasillos'));
    }
    
    public function index_pasillo(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $pasillos = DB::table('pasillo')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('codigo_pasillo', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Ubicaciones.pasillo', compact('pasillos', 'buscar'));
    }

    private function validatePasillo(Request $request)
    {
        $request->validate([
            'codigo_pasillo' => 'required|string',
        ]);
    }

    public function ingreso_P(Request $request)
    {
        $this->validatePasillo($request);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarPasillo 
            @cod_pasillo = ?",
            [$request->codigo_pasillo]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Pasillo')->with('success', 'Pasillo registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoPasillo ?', [$id]);

        return redirect()->route('Pasillo')->with('success', 'Estado del pasillo actualizado');
    }

    public function editar_pasillo($id)
    {
        $pasillo = Pasillo::findOrFail($id);

        return view('Ubicaciones.pasill', compact('pasillo'));
    }

    public function actualizar_pasillo(Request $request, $id)
    {
        $this->validatePasillo($request, [
            'codigo_pasillo' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('pasillo')->ignore($id, 'id_pasillo') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_pasillo
            @id_pasillo= ?, 
            @codigo_pasillo = ?',
            [
                $id, 
                $request->codigo_pasillo, 
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Pasillo')->with('success', 'Pasillo actualizado exitosamente.');
        
    }
}