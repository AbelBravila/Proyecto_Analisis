<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;


class ClienteController extends Controller
{
    public function index_cliente(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $clientes = DB::table('vw_clientes')
            ->where('estado', 'A')
            ->get();
        // Puedes cambiarlo por `paginate()` si deseas paginación
        $tipos_cliente = DB::table('tipo_cliente')->get();

        return view('cliente.cliente', compact('clientes','tipos_cliente', 'buscar'));
    }

    private function validateCliente(Request $request)
    {
        $request->validate([
            'id_tipo_cliente' => 'required|string|max:100',
            'nombre_cliente' => 'required|string|max:100',
            'dpi' => 'required|string|max:15',
            'nit' => 'required|string|max:15',
            'telefono' => 'required|string|max:16',
            'correo' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateCliente($request);

        $nitExistente = Cliente::where('nit', $request->nit)->first();

        if ($nitExistente) {
            return redirect()->route('cliente')->with('error', 'El nit ya pertenece a alguien mas.');
        }

        DB::statement(
            "EXEC sp_Insert_Cliente
            @id_tipo_cliente = ?,
            @nombre_cliente = ?,
            @dpi = ?,
            @nit = ?,
            @telefono = ?,
            @correo =?",
            [$request->id_tipo_cliente, $request->nombre_cliente, $request->dpi, $request->nit, $request->telefono, $request->correo]
        );

        return redirect()->route('cliente')->with('mensaje', 'Cliente registrado exitosamente');
    }

    public function editar_cliente($id)
    {
        $clientes = Cliente::findOrFail($id);

        return view('cliente.cliente', compact('clientes'));
    }

    public function actualizar_cliente(Request $request, $id)
    {
        $this->validateCliente($request, [
            'nit' => [
                'required',
                'string',
                'max:15',
                Rule::unique('cliente', 'nit')->ignore($id, 'id_cliente') // Ignora el producto actual
            ],
        ]);

        // Procedimiento almacenado para actualizar el producto
        DB::statement(
            'EXEC sp_actualizar_Cliente
            @id_cliente = ?,
            @id_tipo_cliente = ?,
            @nombre_cliente = ?,
            @dpi = ?,
            @nit = ?,
            @telefono = ?,
            @correo = ?',
            [
                $id,
                $request->id_tipo_cliente,
                $request->nombre_cliente,
                $request->dpi,
                $request->nit,
                $request->telefono,
                $request->correo
            ]
        );

        // Redirigir con éxito
        return redirect()->route('cliente')->with('mensaje', 'Cliente actualizado exitosamente.');
    }


    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstado_Cliente ?', [$id]);

        return redirect()->route('cliente')->with('mensaje', 'Estado del cliente actualizado');
    }

    public function mostrarDetalles($id)
    {
        $detalles = DB::table('VW_Detalle_Pedidos')
            ->where('id_pedido', $id)
            ->get();

        return view('layouts.partials.admin.detallepedidos', compact('detalles'));
    }
}
