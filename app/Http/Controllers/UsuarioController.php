<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EnviarCorreo;
use App\Mail\ContrasenaTemp;
use APP\Models\User;
use Resend\Laravel\Facades\Resend;



class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $usuarios = DB::table('usuario')->where('estado', 'A')->get();
        return view('Usuario.index', compact('usuarios'));
    }

    public function showRegistrationForm(Request $request)
    {
        
        $buscar = $request->input('buscador');  // Recibe el término de búsqueda

        // Filtra los productos por el término de búsqueda o muestra todos
        $usuarios = DB::table('usuario')->where('estado', 'A')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('codigo_usuario', 'LIKE', "%{$buscar}%")
                             ->orWhere('nombre_usuario', 'LIKE', "%{$buscar}%");
            })
            ->get();  // Puedes cambiarlo por `paginate()` si deseas paginación

        return view('Usuario.index', compact('usuarios', 'buscar'));
    }

    private function validateUsuario(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
            'nombre' => 'required|string|max:100',
            'correo_u' => 'required|string|email|max:100',
            'numero' => 'required|string|max:8'
        ]);
    }

    public function register(Request $request)
    {
        $this->validateUsuario($request);

        $usuarioExistente = DB::table('usuario')->where('correo', $request->correo_u)->first();

        if ($usuarioExistente) {
            return redirect()->route('Usuario')->with('error', 'El correo ya está registrado.');
        }
    
        // Generar una contraseña temporal aleatoria
        $contraseñaTemporal = Str::random(10);

        $empresa = Auth::user()->empresa()->where('estado', 'A')->first()?->id_empresa;

        if (!$empresa) {
            return back()->with('error', 'No se encontró una empresa asociada al usuario.');
        }
        
    
        // Ejecutar el procedimiento almacenado para registrar el usuario con contraseña encriptada
        DB::statement("EXEC sp_RegistrarUsuarioConContrasenaEncriptada 
            @codigo = ?, 
            @nombre = ?, 
            @correo_u = ?, 
            @numero = ?, 
            @contrasenaTemporal = ?,
            @id_nivel = ?, 
            @empresa = ? ", 
            [$request->codigo, $request->nombre, $request->correo_u, $request->numero, $contraseñaTemporal, $request->nivel, $empresa]
        );
        
        // Enviar correo al usuario con la contraseña generada
        $usuario = DB::table('usuario')->where('correo', $request->correo_u)->first();

        if (!$usuario) {
            return redirect()->route('Usuario')->with('error', 'No se encontró el usuario.');
        }
        $correo_u = $request->input('correo_u');
        // Ahora, $usuario es un objeto válido
        //Mail::to($correo_u)->send(new ContrasenaTemp($contraseñaTemporal, $correo_u, $usuario));
        
        Resend::emails()->send([
            'from' => 'soporte@icdigitallink.com',
            'to' => [$correo_u],
            'subject' => 'Bienvenido a POSGT - Tu cuenta ha sido creada',
            'html' => "<h1>Bienvenido a POS Guatemala</h1>
            <p>!Hola {$usuario->nombre_usuario} esta es tu contraseña: <span>{$password}</span></p>",
        ]);

        return redirect()->route('Usuario')->with('mensaje', 'Usuario registrado exitosamente');
    }

    public function cambiar_estado($id)
    {
        DB::statement('EXEC sp_cambiarEstadoUsuario ?', [$id]);

        return redirect()->route('Usuario')->with('mensaje', 'Estado del usuario actualizado');
    }

    public function editar_usuario($id)
    {
        $usuario = User::findOrFail($id);

        return view('Usuario.index', compact('usuario'));
    }

    public function actualizar_usuario(Request $request, $id)
    {
        $this->validateUsuario($request, [
            'codigo_usuario' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('usuario')->ignore($id, 'id_usuario') 
            ],
        ]);
    
  
        DB::statement('EXEC sp_actualizar_usuario
            @id_usuario = ?, 
            @codigo = ?, 
            @nombre = ?, 
            @correo_u = ?, 
            @numero = ?,  
            @id_nivel = ?',
            [
                $id, 
                $request->codigo_usuario, 
                $request->nombre_usuario, 
                $request->correo, 
                $request->numero,
                $request->nivel
            ]
        );
    
        // Redirigir con éxito
        return redirect()->route('Usuario')->with('mensaje', 'Usuario actualizado exitosamente.');
        
    }
}