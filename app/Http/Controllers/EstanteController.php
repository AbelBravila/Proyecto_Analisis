<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class EstanteController extends Controller
{

    public function index_estante()
    {
        $estantes = DB::table('estanteria')->where('estado', 'A')->get();
        return view('Ubicaciones.estante', compact('estantes'));
    }

    public function ingreso_Estante(Request $request)
    {
        $request->validate([
            'codigo_pasillo' => 'required|string',
        ]);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarEstanteria 
            @cod_estanteria = ?,
            @pasillo = ?",
            [$request->codigo_estanteria, $request->pasillo]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Estanteria')->with('success', 'Estanteria registrado exitosamente');
    }
}