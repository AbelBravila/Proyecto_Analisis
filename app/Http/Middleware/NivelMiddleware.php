<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NivelMiddleware
{
    /**
     * Maneja la solicitud entrante.
     */
    public function handle(Request $request, Closure $next, ...$nivelesPermitidos)
    {
        $usuario = Auth::user();

        if (!$usuario) {
            return redirect()->route('login');
        }

        if (!in_array($usuario->id_nivel, $nivelesPermitidos)) {
            return redirect()->route('acceso.denegado');
        }

        return $next($request);
    }
}
