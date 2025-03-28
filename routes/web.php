<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Proveedor;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\ProductoController;

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
})->name('welcome')->middleware('auth');

Route::post('Reset-Password', [LoginController::class, 'resetPassword'])->name('reset');
Route::get('/register/{id}', [LoginController::class, 'registerForm'])->name('register');

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


/*Route::post('Metodo-de-Recuperación', function(){
    Mail::to(request()->email)->send(new EnviarCorreo(request()->password, request()->email));
    return redirect()->route('login')->with('info', 'Se ha enviado un correo con las instrucciones para recuperar la contraseña');
})->name('Metodo-de-Recuperación');
*/
Route::post('Metodo-de-Recuperacion', [RecuperacionController::class, 'recuperarContrasena'])->name('Metodo-de-Recuperacion');



Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/index', [UsuarioController::class, 'showRegistrationForm'])->name('Usuario')->middleware('auth');
Route::post('/index', [UsuarioController::class, 'register'])->middleware('auth');

Route::get('/admin/proveedores', [Proveedor::class, 'index_proveedor'])->name('proveedores')->middleware('auth');
Route::get('/admin/pedidos', [PedidosController::class, 'index_pedidos'])->name('pedidos')->middleware('auth');
Route::post('/admin/pedidos', [PedidosController::class, 'store'])->name('pedidos.guardar')->middleware('auth');
Route::post('/admin/pedidos/buscar', [PedidosController::class, 'buscar'])->name('pedidos.buscar')->middleware('auth');

Route::get('/compras/compras', [ComprasController::class, 'index_compras'])->name('compras')->middleware('auth');

Route::get('/compras/producto', [ProductoController::class, 'index_producto'])->name('producto')->middleware('auth');
Route::post('/compras/producto', [ProductoController::class, 'agregar'])->middleware('auth');
Route::get('/compras/producto/{id}', [ProductoController::class, 'cambiar_estado'])->name('producto.cambiar_estado');
//Route::put('/compras/producto/{id}', [ProductoController::class, 'editar_producto'])->name('producto.editar_producto');
