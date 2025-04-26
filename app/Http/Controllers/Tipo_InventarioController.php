<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TipoInventario;

class Tipo_InventarioController extends Controller
{
    public function index_tipo_inventario(Request $request)
    {
        $buscar = $request->input('buscador');

        $tipos_inventario = TipoInventario::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_tipo_inventario', 'LIKE', "%{$buscar}%")
                    ->orWhere('nombre_tipo_inventario', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('tipo_inventario.tipo_inventario', compact('tipos_inventario', 'buscar'));
    }

    private function validateTipo_Inventario(Request $request)
    {
        $request->validate([
            'nombre_tipo_inventario' => 'required|string|max:50'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateTipo_Inventario($request);

        $nombreExistente = TipoInventario::where('nombre_tipo_inventario', $request->nombre_tipo_inventario)->first();

        if ($nombreExistente) {
            return redirect()->route('tipo_inventario')->with('error', 'El nombre ya le pertenece a alguine más');
        }

        DB::statement(
            "EXEC sp_Insert_Tipo_Inventario
            @nombre_tipo_inventario = ?",
            [$request->nombre_tipo_inventario]
        );

        return redirect()->route('tipo_inventario')->with('success', 'Tipo de inventario registrado correctamente');
    }

    public function editar_tipo_inventario($id)
    {
        $tipos_inventario = TipoInventario::findOrFail($id);

        return view('tipo_inventario.tipo_inventario', compact('tipos_inventario'));
    }

    public function actualizar_tipo_inventario(Request $request, $id)
    {
        $this->validateTipo_Inventario($request, [
            'nombre_tipo_inventario' => [
                'required',
                'string',
                'max:50',
                Rule::unique('nombre_tipo_inventario')->ignore($id, 'id_tipo_inventario') // Ignora el producto actual
            ],
        ]);

        // Procedimiento almacenado para actualizar el producto
        DB::statement(
            'EXEC sp_actualizar_Tipo_Inventario
            @id_tipo_inventario = ?,
            @nombre_tipo_inventario = ?',
            [
                $id,
                $request->nombre_tipo_inventario
            ]
        );

        // Redirigir con éxito
        return redirect()->route('tipo_inventario')->with('success', 'Tipo de inventario actualizado exitosamente.');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoTipo_Inventario ?', [$id]);

        return redirect()->route('tipo_inventario')->with('success', 'Estado del Tipo inventario actualizado');
    }
}
