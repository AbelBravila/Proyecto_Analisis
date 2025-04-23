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
Route::get('/index/{id}', [UsuarioController::class, 'cambiar_estado'])->name('Usuario.cambiar_estado')->middleware('auth');
Route::get('/index/editar_usuario/{id}', [UsuarioController::class, 'editar_usuario'])->name('Usuario.editar_usuario')->middleware('auth');
Route::put('/index/{id}', [UsuarioController::class, 'actualizar_usuario'])->name('Usuario.actualizar_usuario')->middleware('auth');

Route::get('/proveedor/proveedor', [ProveedorController::class, 'index_proveedor'])->name('proveedor')->middleware('auth');
Route::post('/proveedor/proveedor', [ProveedorController::class, 'agregar'])->middleware('auth');
Route::get('/proveedor/proveedor/editar_proveedor/{id}', [ProveedorController::class, 'editar_proveedor'])->name('proveedor.editar_proveedor')->middleware('auth');
Route::put('/proveedor/proveedor/{id}', [ProveedorController::class, 'actualizar_proveedor'])->name('proveedor.actualizar_proveedor')->middleware('auth');
Route::get('/proveedor/proveedor/{id}', [ProveedorController::class, 'cambiar_estado'])->name('proveedor.cambiar_estado')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/admin/pedidos', [PedidosController::class, 'index_pedidos'])->name('pedidos');
    Route::post('/admin/pedidos', [PedidosController::class, 'store'])->name('pedidos.guardar');
    Route::post('/admin/pedidos/buscar', [PedidosController::class, 'buscar'])->name('pedidos.buscar');
    Route::get('/admin/pedidos-realizados', [PedidosController::class, 'VerPedido'])->name('pedidos.realizados');
    //Route::get('/admin/pedidos/editar/{id}', [PedidosController::class, 'editar'])->name('pedidos.editar');
});

Route::get('/compras/compras', [ComprasController::class, 'index_compras'])->name('compras')->middleware('auth');
Route::get('/compras/registrar', [ComprasController::class, 'index_resgistrar'])->name('compras.registrar')->middleware('auth');
Route::post('/compras/crear', [ComprasController::class, 'crearCompra'])->name('compras.crear')->middleware('auth');
Route::get('/compras/compras/anular/{id}', [ComprasController::class, 'anular'])->name('compras.anular')->middleware('auth');
Route::get('/compras/compras/{id}/detalle', [ComprasController::class, 'show'])->name('compras.show');
Route::get('/compras/{id}/detalle', [ComprasController::class, 'mostrarDetalle']);


Route::get('/compras/producto', [ProductoController::class, 'index_producto'])->name('producto')->middleware('auth');
Route::post('/compras/producto', [ProductoController::class, 'agregar'])->middleware('auth');
Route::get('/compras/producto/editar_producto/{id}', [ProductoController::class, 'editar_producto'])->name('producto.editar_producto')->middleware('auth');
Route::put('/compras/producto/{id}', [ProductoController::class, 'actualizar_producto'])->name('producto.actualizar_producto')->middleware('auth');
Route::get('/compras/producto/{id}', [ProductoController::class, 'cambiar_estado'])->name('producto.cambiar_estado')->middleware('auth');

Route::get('/pasillo', [PasilloController::class, 'index_pasillo'])->name('Pasillo')->middleware('auth');
Route::post('/pasillo', [PasilloController::class, 'ingreso_P'])->middleware('auth');

Route::get('/estante', [EstanteController::class, 'index_estante'])->name('Estanteria')->middleware('auth');
Route::post('/estante', [EstanteController::class, 'ingreso_Estante'])->middleware('auth');




Route::get('/admin/devoluciones', [DevolucionesController::class, 'index_devoluciones'])->name('devoluciones')->middleware('auth');


Route::prefix('devoluciones')->name('devoluciones.')->group(function () {
    Route::get('/', [DevolucionController::class, 'index'])->name('index');
    Route::get('/create', [DevolucionController::class, 'create'])->name('create');
    Route::post('/store', [DevolucionController::class, 'store'])->name('store');
    Route::get('/buscar-compras', [DevolucionController::class, 'buscarCompras'])->name('buscar-compras');
    Route::post('/buscar', [DevolucionController::class, 'buscarCompras'])->name('buscar');
    Route::get('/compra/{id}/detalle', [DevolucionController::class, 'detalleCompra'])->name('compra.detalle');
    // Mueve esta ruta al final
    Route::get('/{id}', [DevolucionController::class, 'show'])->name('show');
});
    


Route::put('/estanteria/{id}', [EstanteController::class, 'update'])->name('Estanteria.update');

Route::get('/admin/Tipo_Inventario', [Tipo_InventarioController::class, 'index_Tipo_Inventario'])->name('Tipo_Inventario')->middleware('auth');


Route::get('/compras/producto/{id}/detalles', [ProductoController::class, 'show'])->name('esquema.show');
Route::get('/producto/{id}/detalles', [ProductoController::class, 'mostrarDetalles']);