<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class PasilloController extends Controller
{

    public function index_pasillo()
    {
        $pasillos = DB::table('pasillo')->where('estado', 'A')->get();
        return view('Ubicaciones.pasillo', compact('pasillos'));
    }

    public function ingreso_P(Request $request)
    {
        $request->validate([
            'codigo_pasillo' => 'required|string',
        ]);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_InsertarPasillo 
            @cod_pasillo = ?",
            [$request->codigo_pasillo]
        );
        
        // Enviar correo al usuario con la contraseña generada
        return redirect()->route('Pasillo')->with('success', 'Pasillo registrado exitosamente');
    }
}