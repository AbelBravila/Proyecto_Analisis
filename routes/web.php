<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Proveedor;

use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarCorreo;
use App\Http\Controllers\RecuperacionController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::post('Reset-Password', [LoginController::class, 'resetPassword'])->name('reset');
Route::get('/register/{id}', [LoginController::class, 'registerForm'])->name('register');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*Route::post('Metodo-de-Recuperación', function(){
    Mail::to(request()->email)->send(new EnviarCorreo(request()->password, request()->email));
    return redirect()->route('login')->with('info', 'Se ha enviado un correo con las instrucciones para recuperar la contraseña');
})->name('Metodo-de-Recuperación');
*/
Route::post('Metodo-de-Recuperacion', [RecuperacionController::class, 'recuperarContrasena'])->name('Metodo-de-Recuperacion');



Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/index', [UsuarioController::class, 'showRegistrationForm'])->name('Usuario');
Route::post('/index', [UsuarioController::class, 'register']);

Route::get('/admin/proveedores', [Proveedor::class, 'index_proveedor'])->name('proveedores');
Route::get('/admin/pedidos', [Proveedor::class, 'index_pedidos'])->name('pedidos');
