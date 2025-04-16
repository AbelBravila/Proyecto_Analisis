<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;


class ProveedorController extends Controller
{
    public function index_proveedor(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $proveedores = Proveedor::where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('id_proveedor', 'LIKE', "%{$buscar}%")
                             ->orWhere('nit', 'LIKE', "%{$buscar}%")
                             ->orWhere('nombre_proveedor', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('proveedor.proveedor', compact('proveedores', 'buscar'));
    }

    private function validateProveedor(Request $request)
    {
        $request->validate([
            'nombre_proveedor' => 'required|string|max:100',
            'nit' => 'required|string|max:15',
            'correo' => 'required|string|max:100',
            'telefono' => 'required|string|max:16',
            'direccion' => 'required|string|max:100'
        ]);
    }

    public function agregar(Request $request)
    {
        $this->validateProveedor($request);

        $nitExistente = Proveedor::where('nit', $request->nit)->first();

        if ($nitExistente) {
            return redirect()->route('proveedor')->with('error', 'El nit ya pertenece a alguien mas.');
        }

        DB::statement("EXEC sp_Insert_Proveedor
            @nombre_proveedor = ?,
            @nit = ?,
            @correo =?,
            @telefono = ?,
            @direccion = ?",
            [$request->nombre_proveedor, $request->nit, $request->correo, $request->telefono, $request->direccion]
        );

        return redirect()->route('proveedor')->with('success', 'Proveedor registrado exitosamente');
    }

    public function editar_proveedor($id)
    {
        $proveedores = Proveedor::findOrFail($id);

        return view('proveedor.proveedor', compact('proveedores'));
    }

    public function actualizar_proveedor(Request $request, $id)
    {
        $this->validateProveedor($request, [
            'nit' => [
                'required', 
                'string', 
                'max:15', 
                Rule::unique('nit')->ignore($id, 'id_proveedor') // Ignora el producto actual
            ],
        ]);
    
        // Procedimiento almacenado para actualizar el producto
        DB::statement('EXEC sp_actualizar_proveedor
            @id_proveedor = ?,
            @nombre_proveedor = ?,
            @nit = ?,
            @correo = ?,
	        @telefono = ?,
	        @direccion = ?',
            [
                $id, 
                $request->nombre_proveedor, 
                $request->nit, 
                $request->correo,
                $request->telefono,
                $request->direccion
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('proveedor')->with('success', 'Proveedor actualizado exitosamente.');
        
    }


    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoProveedor ?', [$id]);

        return redirect()->route('proveedor')->with('success', 'Estado del proveedor actualizado');
    }
}
