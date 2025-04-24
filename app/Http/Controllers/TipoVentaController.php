<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\TipoVenta;


class TipoVentaController extends Controller
{

    public function index()
    {
        $ventas = DB::table('tipo_venta')->where('estado', 'A')->get();
        return view('Tipo.tventa', compact('ventas'));
    }
    
    public function index_Tventa(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $ventas = DB::table('tipo_venta')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_tipo_venta', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Tipo.tventa', compact('ventas', 'buscar'));
    }

    private function validateVenta(Request $request)
    {
        $request->validate([
            'nombre_tipo_venta' => 'required|string',
        ]);
    }

    public function ingreso_Tventa(Request $request)
    {
        $this->validateVenta($request);
    
  
        DB::statement("EXEC sp_InsertarTipoVenta 
            @nombre_tipo_venta = ?",
            [$request->nombre_tipo_venta]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Tventa')->with('success', 'Tipo de venta registrada exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoVenta ?', [$id]);

        return redirect()->route('Tventa')->with('success', 'Estado del tipo de venta actualizado');
    }

    public function editar_Tventa($id)
    {
        $tipo_venta = TipoVenta::findOrFail($id);

        return view('Tipo.tventa', compact('tipo_venta'));
    }

    public function actualizar_Tventa(Request $request, $id)
    {
        $this->validateVenta($request, [
            'nombre_tipo_venta' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('tipo_venta')->ignore($id, 'id_tipo_venta') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_venta
            @id_tipo_venta= ?, 
            @nombre_tipo_venta = ?',
            [
                $id, 
                $request->nombre_tipo_venta, 
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Tventa')->with('success', 'Tipo de venta actualizada exitosamente.');
        
    }
}