<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EnviarCorreo;
use App\Mail\ContrasenaTemp;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        return view('Usuario.index');
    }

    public function showRegistrationForm()
    {
      return view('Usuario.index');
    }

    public function register(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'nombre' => 'required|string|max:100',
            'correo_u' => 'required|string|email|max:100',
            'numero' => 'required|string|max:16'
        ]);

        $usuarioExistente = DB::table('usuario')->where('correo', $request->correo_u)->first();

        if ($usuarioExistente) {
            return redirect()->route('Usuario')->with('error', 'El correo ya está registrado.');
        }

        $respuesta = NULL;
        $estado = 'A';
        $nivel = 2;
        $pregunta = NULL;
        $intentos = NULL;
        $temporal = '1';
        $dias = 30;
    
        // Generar una contraseña temporal aleatoria
        $contraseñaTemporal = Str::random(10);
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_RegistrarUsuarioConContrasenaEncriptada 
            @codigo = ?, 
            @nombre = ?, 
            @correo_u = ?, 
            @numero = ?, 
            @contrasenaTemporal = ?,
            @respuesta = ?, 
            @estado = ?, 
            @id_nivel = ?, 
            @id_pregunta = ?, 
            @intentos = ?, 
            @temporal = ?, 
            @dias = ? ", 
            [$request->codigo, $request->nombre, $request->correo_u, $request->numero, $contraseñaTemporal, $respuesta,  $estado, $nivel,  $pregunta, $intentos, $temporal, $dias]
        );
        
        // Enviar correo al usuario con la contraseña generada
        $usuario = DB::table('usuario')->where('correo', $request->correo_u)->first();

        if (!$usuario) {
            return redirect()->route('Usuario')->with('error', 'No se encontró el usuario.');
        }
        $correo_u = $request->input('correo_u');
        // Ahora, $usuario es un objeto válido
        Mail::to($correo_u)->send(new ContrasenaTemp($contraseñaTemporal, $correo_u, $usuario));

        return redirect()->route('Usuario')->with('success', 'Usuario registrado exitosamente');
    }
}