<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TipoCompra;

class Tipo_CompraController extends Controller
{
    public function index_tipo_compra(Request $request)
    {
        $buscar = $request->input('buscador');

        $tipos_compra = TipoCompra::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_tipo_compra', 'LIKE', "%{$buscar}%")
                             ->orWhere('nombre_tipo_compra', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('tipo_compra.tipo_compra', compact('tipos_compra', 'buscar'));
    }

    private function validateTipo_Compra(Request $request)
    {
        $request->validate([
            'nombre_tipo_compra' => 'required|string|max:50'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateTipo_Compra($request);

        $nombreExistente = TipoCompra::where('nombre_tipo_compra', $request->nombre_tipo_compra)->first();

        if ($nombreExistente) {
            return redirect()->route('tipo_compra')->with('error', 'El nombre ya le pertenece a alguine más');
        }

        DB::statement("EXEC sp_Insert_Tipo_Compra
            @nombre_tipo_compra = ?",
            [$request->nombre_tipo_compra]
        );

        return redirect()->route('tipo_compra')->with('success', 'Tipo de compra registrado correctamente');
    }

    public function editar_tipo_compra($id)
    {
        $tipos_compra = TipoCompra::findOrFail($id);

        return view('tipo_compra.tipo_compra', compact('tipos_compra'));
    }

    public function actualizar_tipo_compra(Request $request, $id)
    {
        $this->validateTipo_Compra($request, [
            'nombre_tipo_compra' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('nombre_tipo_compra')->ignore($id, 'id_tipo_compra') // Ignora el producto actual
            ],
        ]);
    
        // Procedimiento almacenado para actualizar el producto
        DB::statement('EXEC sp_actualizar_Tipo_Compra
            @id_tipo_compra = ?,
            @nombre_tipo_compra = ?',
            [
                $id, 
                $request->nombre_tipo_compra
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('tipo_compra')->with('success', 'Tipo de Compra actualizado exitosamente.');
        
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoTipo_Compra ?', [$id]);

        return redirect()->route('tipo_compra')->with('success', 'Estado del Tipo Compra actualizado');
    }
}
