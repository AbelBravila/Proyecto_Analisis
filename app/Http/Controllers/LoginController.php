<?php
 namespace App\Http\Controllers;
 use App\Http\Controllers\Controller;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Hash;
 class LoginController extends Controller
 {
        public function showLoginForm()
        {
            return view('auth.login');
        }
        public function login(Request $request)
        {
            $credentials = $request->only('correo', 'contrasena');
    
            // Llamar al procedimiento almacenado
            $usuario = DB::selectOne('EXEC sp_ObtenerUsuarioContrasena ?', [$credentials['correo']]);
    
            // Verificar si el usuario existe
            if (empty($usuario)) {
                return back()->withErrors(['correo' => 'Usuario no encontrado'])->withInput();
            }
    
            // $usuario = $usuario[0]; // Obtener el primer resultado
    
            // Verificar si el usuario está inactivo
            if ($usuario->estado === 'I') {
                return back()->withErrors(['inactivo' => 'El usuario está inactivo'])->withInput();
            }
    
            // Verificar si el usuario está bloqueado por intentos fallidos
            if ($usuario->intentos >= 5) {
                return back()->withErrors(['bloqueado' => 'Usuario bloqueado. Contacte al administrador.'])->withInput();
            }
            if ($usuario->temporal == '1') {
                return redirect()->route('register',$usuario->id_usuario);
            }
    
            // Verificar la contraseña ingresada con la desencriptada
            if ($credentials['contrasena'] === $usuario->contrasena_desencriptada) {
                // Restablecer intentos fallidos
                DB::table('usuario')
                    ->where('id_usuario', $usuario->id_usuario)
                    ->update(['intentos' => 0]);


                    $user = new \App\Models\User([
                       'id_usuario' => $usuario->id_usuario,
                       'nombre_usuario' => $usuario->nombre_usuario,
                       'correo' => $usuario->correo,
                       'estado' => $usuario->estado
                    ]);

                    $respuesta=DB::select("EXEC VerificarCambioContrasena '$usuario->id_usuario'");
 
                    if ($respuesta[0]->cambio=='1') {
                        return redirect()->route('register',$usuario->id_usuario);
                    }
                    else{
                        Auth::login($user);
                        return redirect()->intended('welcome');
                    }
                
            }
    
            // Incrementar intentos fallidos
            DB::table('usuario')
                ->where('id_usuario', $usuario->id_usuario)
                ->update(['intentos' => $usuario->intentos + 1]);
    
            return back()->withErrors(['contrasena' => 'Contraseña incorrecta'])->withInput();
        }
        public function logout()
        {
            Auth::logout(); 
            return redirect('login');
        }
        public function registerForm($id)
        {
            return view('auth.register',['id'=>$id]); 
        }
    
        // Manejar la solicitud de restablecimiento de contraseña
        public function resetPassword(Request $request)
        {
            if($request->password==$request->password_confirmation){
                $respuesta=DB::select("EXEC sp_CambiarContrasena '$request->id', '$request->password'");
    
    
                if($respuesta[0]->mensaje=='La nueva contraseña no puede ser similar a una contraseña anterior.'){
    
                    return back()->withErrors(['contrasena' => $respuesta[0]->mensaje])->withInput();
                }   
                return redirect()->route('login')->with('info', 'Contraseña restablecida correctamente. Por favor, inicia sesión.');
            }
            else {
                return back()->withErrors(['contrasena' => 'La contraseña no coincide'])->withInput();
            }
    
        }
}