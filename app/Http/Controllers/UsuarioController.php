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
    'html' => '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Bienvenido a POS GT</title>
<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
        color: #333;
    }
    .container {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .header-simple {
        text-align: center;
        background-color: #002855;
        color: white;
        padding: 30px 20px;
    }
    .header-simple img.logo-simple {
        max-width: 80px;
        height: auto;
        border-radius: 8px;
    }
    .sistema-title {
        margin: 10px 0 0;
        font-size: 22px;
        letter-spacing: 1px;
    }
    .content {
        padding: 30px 40px;
        text-align: center;
    }
    h1 {
        font-size: 24px;
        color: #002855;
        margin-bottom: 15px;
    }
    p {
        font-size: 16px;
        line-height: 1.6;
        margin: 10px 0;
    }
    .password-box {
        background-color: #e8f0fe;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        color: #002855;
        margin: 15px 0;
    }
    .button {
        display: inline-block;
        padding: 12px 24px;
        background-color: #002855;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        margin-top: 20px;
    }
    .footer {
        background-color: #f0f0f0;
        text-align: center;
        padding: 15px;
        font-size: 13px;
        color: #777;
    }
</style>
</head>
<body>
    <div class="container">
        <div class="header-simple">
            <img src="data:image/jpeg;base64,'.base64_encode(file_get_contents(resource_path("images/logo.jpg"))).'" alt="Logo" class="logo-simple">
            <h1 class="sistema-title">SISTEMA POS GT</h1>
        </div>

        <div class="content">
            <h1>¡Bienvenido a POS Guatemala!</h1>
            <p>Hola <strong>'.$usuario->nombre_usuario.'</strong>, tu cuenta ha sido creada exitosamente.</p>
            <p>Esta es tu contraseña temporal:</p>
            <div class="password-box">'.$contraseñaTemporal.'</div>
            <p>Por tu seguridad, te recomendamos cambiarla la próxima vez que inicies sesión.</p>
            <a href="https://posgt.icdigitallink.com" class="button">Ir al Sistema</a>
        </div>

        <div class="footer">
            © '.date("Y").' POS GT | Desarrollado por <strong>IC Digital Link</strong>
        </div>
    </div>
</body>
</html>'
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