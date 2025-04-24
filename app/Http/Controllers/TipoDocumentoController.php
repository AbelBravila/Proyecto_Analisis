<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Documento;


class TipoDocumentoController extends Controller
{

    public function index()
    {
        $documento = DB::table('tipo_documento')->where('estado', 'A')->get();
        return view('Ubicaciones.pasillo', compact('pasillos'));
    }
    
    public function index_documento(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $documentos = DB::table('tipo_documento')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_documento', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Tipo.tdocumento', compact('documentos', 'buscar'));
    }

    private function validateDocumento(Request $request)
    {
        $request->validate([
            'nombre_documento' => 'required|string',
        ]);
    }

    public function ingreso_Documento(Request $request)
    {
        $this->validateDocumento($request);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarTipoDocumento 
            @nombre_documento = ?",
            [$request->nombre_documento]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Documento')->with('success', 'Documento registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoDocumento ?', [$id]);

        return redirect()->route('Documento')->with('success', 'Estado del documento actualizado');
    }

    public function editar_documento($id)
    {
        $tipo_documento = TipoDocumento::findOrFail($id);

        return view('Tipo.tdocumento', compact('tipo_documento'));
    }

    public function actualizar_documento(Request $request, $id)
    {
        $this->validateDocumento($request, [
            'nombre_documento' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('tipo_documento')->ignore($id, 'id_tipo_documento') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_documento
            @id_tipo_documento= ?, 
            @nombre_documento = ?',
            [
                $id, 
                $request->nombre_documento, 
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Documento')->with('success', 'Documento actualizado exitosamente.');
        
    }
}