<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;

class NotificacionController extends Controller
{
    public function enviarPush(FirebaseService $firebase)
    {
        $titulo = "Autorización";
        $mensaje = "Hay una nueva venta para autorizar";

        $tokens = DB::table('Tokens_Dispositivos')
            ->where('Estado', 1)
            ->pluck('TokenDispositivo')
            ->toArray();

        $enviadas = 0;
        $fallidas = 0;

        foreach ($tokens as $token) {
            try {
                $firebase->sendNotificationToDevice($token, $titulo, $mensaje);
                $enviadas++;
            } catch (\Throwable $th) {
                \Log::error("Error enviando a token $token: " . $th->getMessage());
                $fallidas++;
            }
        }

        // return response()->json([
        //     'message' => 'Notificaciones enviadas',
        //     'enviadas' => $enviadas,
        //     'fallidas' => $fallidas,
        // ]);
    }
}
