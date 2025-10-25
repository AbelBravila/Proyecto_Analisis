<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCorreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Resend\Laravel\Facades\Resend;

class RecuperacionController extends Controller
{
    public function recuperarContrasena(Request $request)
    {
        $correo = $request->input('email');

        // Verificar si el correo existe en la base de datos
        $usuario = DB::table('usuario')->where('correo', $correo)->first();

        if (! $usuario) {
            return redirect()->route('password.request')->with('error', 'El correo no está registrado.');
        }

        // Verificar si el usuario está inactivo
        if ($usuario->estado === 'I') {
            return redirect()->route('password.request')->with('error', 'El usuario está inactivo.');
        }

        // Generar una nueva contraseña aleatoria
        $nuevaContrasena = Str::random(10);

        // Actualizar la contraseña en la base de datos y marcarla como temporal
        DB::table('usuario')
            ->where('correo', $correo)
            ->update([
                'temporal' => '1',
            ]);
        DB::statement("EXEC sp_EncriptarContrasena '$correo', '$nuevaContrasena'");

        // Enviar el correo con la nueva contraseña
        // Mail::to($correo)->send(new EnviarCorreo($nuevaContrasena, $correo, $usuario));

        $resend = Resend::client('re_JBGFdL3L_5kzDPPF6vTnJrwaE8BKRoeYH');

        $resend->emails->send([
            'from' => 'Soporte POS <soportesistemaposgt@gmail.com>',
            'to' => [$correo],
            'subject' => 'Recuperación de contraseña',
            'html' => "<p>¡Hola <b>{$usuario->nombre}</b>!<br>
                   Esta es tu nueva contraseña: 
                   <span style='color:red; font-weight:bold;'>{$nuevaContrasena}</span></p>",
        ]);

        return redirect()->route('login')->with('info', 'Se ha enviado un correo con la nueva contraseña.');
    }
}
