<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\DevolucionesController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PasilloController;
use App\Http\Controllers\EstanteController;
use App\Http\Controllers\Tipo_InventarioController;


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

Route::get('/admin/proveedores', [ProveedorController::class, 'index_proveedor'])->name('Proveedores')->middleware('auth');
Route::post('/admin/proveedores', [ProveedorController::class, 'store'])->name('Proveedores.Guardar')->middleware('auth');
Route::get('/admin/proveedores/editar', [ProveedorController::class, 'editar'])->name('Proveedores.Editar')->middleware('auth');
Route::put('/admin/proveedores', [ProveedorController::class, 'update'])->name('Proveedores.Actualizar')->middleware('auth');
Route::delete('/admin/proveedores', [ProveedorController::class, 'destroy'])->name('Proveedores.Eliminar')->middleware('auth');

Route::get('/admin/pedidos', [PedidosController::class, 'index_pedidos'])->name('pedidos')->middleware('auth');
Route::post('/admin/pedidos', [PedidosController::class, 'store'])->name('pedidos.guardar')->middleware('auth');
Route::post('/admin/pedidos/buscar', [PedidosController::class, 'buscar'])->name('pedidos.buscar')->middleware('auth');

Route::get('/compras/compras', [ComprasController::class, 'index_compras'])->name('compras')->middleware('auth');

Route::get('/compras/producto', [ProductoController::class, 'index_producto'])->name('producto')->middleware('auth');
Route::post('/compras/producto', [ProductoController::class, 'agregar'])->middleware('auth');
Route::get('/compras/producto/{id}', [ProductoController::class, 'cambiar_estado'])->name('producto.cambiar_estado');
//Route::put('/compras/producto/{id}', [ProductoController::class, 'editar_producto'])->name('producto.editar_producto');

Route::get('/pasillo', [PasilloController::class, 'index_pasillo'])->name('Pasillo')->middleware('auth');
Route::post('/pasillo', [PasilloController::class, 'ingreso_P'])->middleware('auth');

Route::get('/estante', [EstanteController::class, 'index_estante'])->name('Estanteria')->middleware('auth');
Route::post('/estante', [EstanteController::class, 'ingreso_Estante'])->middleware('auth');

<<<<<<< HEAD
Route::put('/estanteria/{id}', [EstanteriaController::class, 'update'])->name('Estanteria.update');



Route::get('/admin/devoluciones', [DevolucionesController::class, 'index_devoluciones'])->name('devoluciones')->middleware('auth');


Route::prefix('devoluciones')->name('devoluciones.')->group(function () {
    Route::get('/', [DevolucionController::class, 'index'])->name('index');
    Route::get('/crear', [DevolucionController::class, 'create'])->name('create');
    Route::post('/', [DevolucionController::class, 'store'])->name('store');
    Route::get('/{id}', [DevolucionController::class, 'show'])->name('show');
});
=======
Route::put('/estanteria/{id}', [EstanteController::class, 'update'])->name('Estanteria.update');

Route::get('/admin/Tipo_Inventario', [Tipo_InventarioController::class, 'index_Tipo_Inventario'])->name('Tipo_Inventario')->middleware('auth');
>>>>>>> 8584a5d31e99cfc1fc787df2c4a700d422a1be3f
