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
use App\Http\Controllers\Tipo_ClienteController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarCorreo;
use App\Http\Controllers\RecuperacionController;
use App\Http\Controllers\Tipo_CompraController;
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

Route::get('/proveedor/proveedor', [ProveedorController::class, 'index_proveedor'])->name('proveedor')->middleware('auth');
Route::post('/proveedor/proveedor', [ProveedorController::class, 'agregar'])->middleware('auth');
Route::get('/proveedor/proveedor/editar_proveedor/{id}', [ProveedorController::class, 'editar_proveedor'])->name('proveedor.editar_proveedor')->middleware('auth');
Route::put('/proveedor/proveedor/{id}', [ProveedorController::class, 'actualizar_proveedor'])->name('proveedor.actualizar_proveedor')->middleware('auth');
Route::get('/proveedor/proveedor/{id}', [ProveedorController::class, 'cambiar_estado'])->name('proveedor.cambiar_estado')->middleware('auth');

// Tipo Compra (Isaac)
Route::get('/tipo_compra/tipo_compra', [Tipo_CompraController::class, 'index_tipo_compra'])->name('tipo_compra')->middleware('auth');
Route::post('/tipo_compra/tipo_compra', [Tipo_CompraController::class, 'agregar'])->middleware('auth');
Route::get('/tipo_compra/tipo_compra/editar_tipo_compra/{id}', [Tipo_CompraController::class, 'editar_tipo_compra'])->name('tipo_compra.editar_tipo_compra')->middleware('auth');
Route::put('/tipo_compra/tipo_compra/{id}', [Tipo_CompraController::class, 'actualizar_tipo_compra'])->name('tipo_compra.actualizar_tipo_compra')->middleware('auth');
Route::get('/tipo_compra/tipo_compra/{id}', [Tipo_CompraController::class, 'cambiar_estado'])->name('tipo_compra.cambiar_estado')->middleware('auth');


// Tipo Inventario (Isaac)
Route::get('/tipo_inventario/tipo_inventario', [Tipo_InventarioController::class, 'index_tipo_inventario'])->name('tipo_inventario')->middleware('auth');
Route::post('/tipo_inventario/tipo_inventario', [Tipo_InventarioController::class, 'agregar'])->middleware('auth');
Route::get('/tipo_inventario/tipo_inventario/editar_tipo_inventario/{id}', [Tipo_InventarioController::class, 'editar_tipo_inventario'])->name('tipo_inventario.editar_tipo_inventario')->middleware('auth');
Route::put('/tipo_inventario/tipo_inventario/{id}', [Tipo_InventarioController::class, 'actualizar_tipo_inventario'])->name('tipo_inventario.actualizar_tipo_inventario')->middleware('auth');
Route::get('/tipo_inventario/tipo_inventario/{id}', [Tipo_InventarioController::class, 'cambiar_estado'])->name('tipo_inventario.cambiar_estado')->middleware('auth');

// Tipo Clinte (Isaac)
Route::get('/tipo_cliente/tipo_cliente', [Tipo_ClienteController::class, 'index_tipo_cliente'])->name('tipo_cliente')->middleware('auth');
Route::post('/tipo_cliente/tipo_cliente', [Tipo_ClienteController::class, 'agregar'])->middleware('auth');
Route::get('/tipo_cliente/tipo_cliente/editar_tipo_cliente/{id}', [Tipo_ClienteController::class, 'editar_tipo_cliente'])->name('tipo_cliente.editar_tipo_cliente')->middleware('auth');
Route::put('/tipo_cliente/tipo_cliente/{id}', [Tipo_ClienteController::class, 'actualizar_tipo_cliente'])->name('tipo_cliente.actualizar_tipo_cliente')->middleware('auth');
Route::get('/tipo_cliente/tipo_cliente/{id}', [Tipo_ClienteController::class, 'cambiar_estado'])->name('tipo_cliente.cambiar_estado')->middleware('auth');

// Cliente
Route::get('/cliente/cliente', [ClienteController::class, 'index_cliente'])->name('cliente')->middleware('auth');
Route::post('/cliente/cliente', [ClienteController::class, 'agregar'])->middleware('auth');
Route::get('/cliente/cliente/editar_cliente/{id}', [ClienteController::class, 'editar_cliente'])->name('cliente.editar_cliente')->middleware('auth');
Route::put('/cliente/cliente/{id}', [ClienteController::class, 'actualizar_cliente'])->name('cliente.actualizar_cliente')->middleware('auth');
Route::get('/cliente/cliente/{id}', [ClienteController::class, 'cambiar_estado'])->name('cliente.cambiar_estado')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/admin/pedidos', [PedidosController::class, 'index_pedidos'])->name('pedidos');
    Route::post('/admin/pedidos', [PedidosController::class, 'store'])->name('pedidos.guardar');
    Route::post('/admin/pedidos/buscar', [PedidosController::class, 'buscar'])->name('pedidos.buscar');
});

Route::get('/compras/compras', [ComprasController::class, 'index_compras'])->name('compras')->middleware('auth');
Route::get('/compras/registrar', [ComprasController::class, 'index_resgistrar'])->name('compras.registrar')->middleware('auth');
Route::post('/compras/crear', [ComprasController::class, 'crearCompra'])->name('compras.crear')->middleware('auth');


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
