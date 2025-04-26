<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\TipoCliente;

class Tipo_ClienteController extends Controller
{
    public function index_tipo_cliente(Request $request)
    {
        $buscar = $request->input('buscador');

        $tipos_cliente = TipoCliente::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_tipo_cliente', 'LIKE', "%{$buscar}%")
                    ->orWhere('nombre_tipo_cliente', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('tipo_cliente.tipo_cliente', compact('tipos_cliente', 'buscar'));
    }

    private function validateTipo_Cliente(Request $request)
    {
        $request->validate([
            'nombre_tipo_cliente' => 'required|string|max:50'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateTipo_Cliente($request);

        $nombreExistente = TipoCliente::where('nombre_tipo_cliente', $request->nombre_tipo_cliente)->first();

        if ($nombreExistente) {
            return redirect()->route('tipo_cliente')->with('error', 'El nombre ya le pertenece a alguine más');
        }

        DB::statement(
            "EXEC sp_Insert_Tipo_Cliente
            @nombre_tipo_cliente = ?,
            @descuento = ?",
            [$request->nombre_tipo_cliente, $request->descuento]
        );

        return redirect()->route('tipo_cliente')->with('success', 'Tipo de cliente registrado correctamente');
    }

    public function editar_tipo_cliente($id)
    {
        $tipos_cliente = TipoCliente::findOrFail($id);

        return view('tipo_cliente.tipo_cliente', compact('tipos_cliente'));
    }

    public function actualizar_tipo_cliente(Request $request, $id)
    {
        $this->validateTipo_Cliente($request, [
            'nombre_tipo_cliente' => [
                'required',
                'string',
                'max:50',
                Rule::unique('nombre_tipo_cliente')->ignore($id, 'id_tipo_cliente') // Ignora el producto actual
            ],
        ]);

        // Procedimiento almacenado para actualizar el producto
        DB::statement(
            'EXEC sp_actualizar_Tipo_Cliente
            @id_tipo_cliente = ?,
            @nombre_tipo_cliente = ?,
            @descuento = ?',
            [
                $id,
                $request->nombre_tipo_cliente, 
                $request->descuento
            ]
        );

        // Redirigir con éxito
        return redirect()->route('tipo_cliente')->with('success', 'Tipo de cliente actualizado exitosamente.');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoTipo_Cliente ?', [$id]);

        return redirect()->route('tipo_cliente')->with('success', 'Estado del Tipo cliente actualizado');
    }
}
