<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\TipoPago;


class TipoPagoController extends Controller
{

    public function index()
    {
        $pagos = DB::table('tipo_pago')->where('estado', 'A')->get();
        return view('Tipo.tpago', compact('pagos'));
    }
    
    public function index_pago(Request $request)
    {
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $pagos = DB::table('tipo_pago')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre_tipo_pago', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Tipo.tpago', compact('pagos', 'buscar'));
    }

    private function validatePago(Request $request)
    {
        $request->validate([
            'nombre_tipo_pago' => 'required|string',
        ]);
    }

    public function ingreso_Pago(Request $request)
    {
        $this->validatePago($request);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarTipoPago 
            @nombre_tipo_pago = ?",
            [$request->nombre_tipo_pago]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Pago')->with('success', 'Tipo de pago registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoPago ?', [$id]);

        return redirect()->route('Pago')->with('success', 'Estado del tipo de pago actualizado');
    }

    public function editar_pago($id)
    {
        $tipo_pago = TipoPago::findOrFail($id);

        return view('Tipo.tpago', compact('tipo_pago'));
    }

    public function actualizar_pago(Request $request, $id)
    {
        $this->validatePago($request, [
            'nombre_tipo_pago' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('tipo_pago')->ignore($id, 'id_tipo_pago') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_pago
            @id_tipo_pago= ?, 
            @nombre_tipo_pago = ?',
            [
                $id, 
                $request->nombre_tipo_pago, 
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Pago')->with('success', 'Tipo de pago actualizado exitosamente.');
        
    }
}