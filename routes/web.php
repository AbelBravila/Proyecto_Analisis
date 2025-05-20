<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CajasController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\AperturaCajaController;
use App\Http\Controllers\AsignacionSatController;
use App\Http\Controllers\CierreCajaController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\DevolucionesController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PasilloController;
use App\Http\Controllers\EstanteController;
use App\Http\Controllers\Tipo_InventarioController;
use App\Http\Controllers\OfertasController;
use App\Http\Controllers\Tipo_ClienteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DevolucionVentaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\TipoVentaController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\PresentacionController;


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
Route::get('/index/{id}', [UsuarioController::class, 'cambiar_estado'])->name('Usuario.cambiar_estado')->middleware('auth');
Route::get('/index/editar_usuario/{id}', [UsuarioController::class, 'editar_usuario'])->name('Usuario.editar_usuario')->middleware('auth');
Route::put('/index/{id}', [UsuarioController::class, 'actualizar_usuario'])->name('Usuario.actualizar_usuario')->middleware('auth');

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

//PEDIDOS
Route::middleware('auth')->group(function () {
    Route::get('/admin/pedidos', [PedidosController::class, 'index_pedidos'])->name('pedidos');
    Route::post('/admin/pedidos', [PedidosController::class, 'store'])->name('pedidos.guardar');
    Route::get('/admin/pedidos-realizados', [PedidosController::class, 'VerPedido'])->name('pedidos.realizados');
    Route::get('/admin/pedidos/eliminar/{id}', [PedidosController::class, 'eliminarPedido'])->name('pedidos.eliminar');
    Route::get('/pedidos/{id}/detalles', [PedidosController::class, 'mostrarDetalles']);
    Route::get('/compras/desde-pedido/{id_pedido}', [ComprasController::class, 'createFromPedido'])->name('compras.fromPedido');

});

//COMPRAS
Route::get('/compras/compras', [ComprasController::class, 'index_compras'])->name('compras')->middleware('auth');
Route::get('/compras/registrar', [ComprasController::class, 'index_resgistrar'])->name('compras.registrar')->middleware('auth');
Route::post('/compras/crear', [ComprasController::class, 'crearCompra'])->name('compras.crear')->middleware('auth');
Route::get('/compras/compras/anular/{id}', [ComprasController::class, 'anular'])->name('compras.anular')->middleware('auth');
Route::get('/compras/compras/{id}/detalle', [ComprasController::class, 'show'])->name('compras.show');
Route::get('/compras/{id}/detalle', [ComprasController::class, 'mostrarDetalle']);


//PRODUCTO
Route::get('/compras/producto', [ProductoController::class, 'index_producto'])->name('producto')->middleware('auth');
Route::post('/compras/producto', [ProductoController::class, 'agregar'])->middleware('auth');
Route::get('/compras/producto/editar_producto/{id}', [ProductoController::class, 'editar_producto'])->name('producto.editar_producto')->middleware('auth');
Route::put('/compras/producto/{id}', [ProductoController::class, 'actualizar_producto'])->name('producto.actualizar_producto')->middleware('auth');
Route::get('/compras/producto/{id}', [ProductoController::class, 'cambiar_estado'])->name('producto.cambiar_estado')->middleware('auth');

//OFERTAS
Route::middleware('auth')->group(function () {
    Route::get('/ofertas',            [OfertasController::class, 'index'])->name('ofertas');
    Route::get('/ofertas/create',     [OfertasController::class, 'create'])->name('ofertas.create');
    Route::post('/ofertas',           [OfertasController::class, 'store'])->name('ofertas.store');
    Route::post('/producto/buscar',   [OfertasController::class, 'buscarPorNombre'])->name('producto.buscar.nombre');
    Route::get('/ofertas/{id}/detalles',[OfertasController::class,'mostrarDetalles'])->name('ofertas.detalles');
    Route::get('/ofertas/eliminar/{id}',[OfertasController::class, 'eliminarOferta'])->name('ofertas.eliminar');

});

//VENTAS
Route::get('/ventas/ventas', [VentaController::class, 'index_ventas'])->name('ventas')->middleware('auth');
Route::get('/ventas/registrar', [VentaController::class, 'index_registrar'])->name('ventas.registrar')->middleware('auth');
Route::post('/ventas/crear', [VentaController::class, 'crearVenta'])->name('ventas.crear')->middleware('auth');
Route::get('/ventas/ventas/anular/{id}', [VentaController::class, 'anular'])->name('ventas.anular')->middleware('auth');
Route::get('/ventas/ventas/{id}/detalle', [VentaController::class, 'show'])->name('ventas.show');
Route::get('/ventas/{id}/detalle', [VentaController::class, 'mostrarDetalle']);

Route::get('/pasillo', [PasilloController::class, 'index_pasillo'])->name('Pasillo')->middleware('auth');
Route::post('/pasillo', [PasilloController::class, 'ingreso_P'])->middleware('auth');
Route::get('/pasillo/{id}', [PasilloController::class, 'cambiar_estado'])->name('Pasillo.cambiar_estado')->middleware('auth');
Route::get('/pasillo/editar_pasillo/{id}', [PasilloController::class, 'editar_pasillo'])->name('Pasillo.editar_pasillo')->middleware('auth');
Route::put('/pasillo/{id}', [PasilloController::class, 'actualizar_pasillo'])->name('Pasillo.actualizar_pasillo')->middleware('auth');

Route::get('/estante', [EstanteController::class, 'index_estante'])->name('Estanteria')->middleware('auth');
Route::post('/estante', [EstanteController::class, 'ingreso_Estante'])->middleware('auth');
Route::get('/estante/{id}', [EstanteController::class, 'cambiar_estado'])->name('Estanteria.cambiar_estado')->middleware('auth');
Route::get('/estante/editar_estante/{id}', [EstanteController::class, 'editar_usuario'])->name('Estanteria.editar_estante')->middleware('auth');
Route::put('/estante/{id}', [EstanteController::class, 'actualizar_estante'])->name('Estanteria.actualizar_estante')->middleware('auth');

Route::get('/tipo_documento', [TipoDocumentoController::class, 'index_documento'])->name('Documento')->middleware('auth');
Route::post('/tipo_documento', [TipoDocumentoController::class, 'ingreso_Documento'])->middleware('auth');
Route::get('/tipo_documento/{id}', [TipoDocumentoController::class, 'cambiar_estado'])->name('Documento.cambiar_estado')->middleware('auth');
Route::get('/tipo_documento/editar_documento/{id}', [TipoDocumentoController::class, 'editar_documento'])->name('Documento.editar_documento')->middleware('auth');
Route::put('/pasitipo_documentolo/{id}', [TipoDocumentoController::class, 'actualizar_documento'])->name('Documento.actualizar_documento')->middleware('auth');

Route::get('/tipo_pago', [TipoPagoController::class, 'index_pago'])->name('Pago')->middleware('auth');
Route::post('/tipo_pago', [TipoPagoController::class, 'ingreso_Pago'])->middleware('auth');
Route::get('/tipo_pago/{id}', [TipoPagoController::class, 'cambiar_estado'])->name('Pago.cambiar_estado')->middleware('auth');
Route::get('/tipo_pago/editar_documento/{id}', [TipoPagoController::class, 'editar_pago'])->name('Pago.editar_pago')->middleware('auth');
Route::put('/tipo_pago/{id}', [TipoPagoController::class, 'actualizar_pago'])->name('Pago.actualizar_pago')->middleware('auth');

Route::get('/tipo_venta', [TipoVentaController::class, 'index_Tventa'])->name('Tventa')->middleware('auth');
Route::post('/tipo_venta', [TipoVentaController::class, 'ingreso_Tventa'])->middleware('auth');
Route::get('/tipo_venta/{id}', [TipoVentaController::class, 'cambiar_estado'])->name('Tventa.cambiar_estado')->middleware('auth');
Route::get('/tipo_venta/editar_documento/{id}', [TipoVentaController::class, 'editar_Tventa'])->name('Tventa.editar_Tventa')->middleware('auth');
Route::put('/tipo_venta/{id}', [TipoVentaController::class, 'actualizar_Tventa'])->name('Tventa.actualizar_Tventa')->middleware('auth');

Route::get('/presentacion', [PresentacionController::class, 'index_presentacion'])->name('Presentacion')->middleware('auth');
Route::post('/presentacion', [PresentacionController::class, 'ingreso_presentacion'])->middleware('auth');
Route::get('/presentacion/{id}', [PresentacionController::class, 'cambiar_estado'])->name('Presentacion.cambiar_estado')->middleware('auth');
Route::get('/presentacion/editar_documento/{id}', [PresentacionController::class, 'editar_presentacion'])->name('Presentacion.editar_presentacion')->middleware('auth');
Route::put('/presentacion/{id}', [PresentacionController::class, 'actualizar_presentacion'])->name('Presentacion.actualizar_presentacion')->middleware('auth');


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

Route::prefix('devoluciones_venta')->name('devoluciones_venta.')->group(function () {
    Route::get('/', [DevolucionVentaController::class, 'index'])->name('index');
    Route::get('/create', [DevolucionVentaController::class, 'create'])->name('create');
    Route::post('/store', [DevolucionVentaController::class, 'store'])->name('store');
    // Rutas AJAX para búsqueda de ventas
    Route::post('/buscar', [DevolucionVentaController::class, 'buscar'])->name('buscar');
    Route::get('/buscar-ventas', [DevolucionVentaController::class, 'buscarVentas'])->name('buscar-ventas');
    Route::get('/detalle/{id}', [DevolucionVentaController::class, 'detalle'])->name('venta.detalle');
// Detalle de venta seleccionada
    Route::get('/venta/{id}/detalle', [DevolucionVentaController::class, 'detalleVenta'])->name('venta.detalle');
    // Ver detalles de una devolución específica (última ruta por convención)
    Route::get('/{id}', [DevolucionVentaController::class, 'show'])->name('show');
});


Route::put('/estanteria/{id}', [EstanteController::class, 'update'])->name('Estanteria.update');

Route::get('/admin/Tipo_Inventario', [Tipo_InventarioController::class, 'index_Tipo_Inventario'])->name('Tipo_Inventario')->middleware('auth');


Route::get('/compras/producto/{id}/detalles', [ProductoController::class, 'show'])->name('esquema.show');
Route::get('/producto/{id}/detalles', [ProductoController::class, 'mostrarDetalles']);


Route::get('/caja/caja', [CajasController::class, 'index_cajas'])->name('cajas')->middleware('auth');
Route::post('/caja/caja', [CajasController::class, 'agregar'])->middleware('auth');
Route::get('/caja/caja/editar_caja/{id}', [CajasController::class, 'editar_caja'])->name('cajas.editar_caja')->middleware('auth');
Route::put('/caja/caja/{id}', [CajasController::class, 'actualizar_cajas'])->name('cajas.actualizar_cajas')->middleware('auth');
Route::get('/caja/caja/{id}', [CajasController::class, 'cambiar_estado'])->name('cajas.cambiar_estado')->middleware('auth');

Route::get('/turno/turno', [TurnoController::class, 'index_turno'])->name('turnos')->middleware('auth');
Route::post('/turno/turno', [TurnoController::class, 'agregar'])->middleware('auth');
Route::get('/turno/turno/editar_turno/{id}', [TurnoController::class, 'editar_turno'])->name('turnos.editar_turno')->middleware('auth');
Route::put('/turno/turno/{id}', [TurnoController::class, 'actualizar_turnos'])->name('turnos.actualizar_turnos')->middleware('auth');
Route::post('/turno/turno/{id}', [TurnoController::class, 'cambiar_estado'])->name('turnos.cambiar_estado')->middleware('auth');

// Route::get('/cajas-disponibles', [AperturaCajaController::class, 'obtenerCajasDisponibles']);
// Route::post('/apertura-caja', [TuControlador::class, 'store'])->name('apertura-caja.store');

Route::get('/asignacion-caja', [AsignacionSatController::class, 'index'])->name('asignacion-caja.index');
Route::post('/asignacion-caja', [AsignacionSatController::class, 'store'])->name('asignacion-caja.store');
Route::put('/asignacion-caja/{id}', [AsignacionSatController::class, 'update'])->name('asignacion-caja.update');
Route::get('/asignacion-caja-delete/{id}', [AsignacionSatController::class, 'destroy'])->name('asignacion-caja.destroy');

Route::get('/apertura-caja/{id}/cajas', [AperturaCajaController::class, 'cajasPorUsuario']);

Route::get('/apertura-caja/{id}/movimientos-json', [AperturaCajaController::class, 'movimientosJson']);
Route::get('/caja/cerrar/{id}', [AperturaCajaController::class, 'cerrarCaja'])->name('caja.cerrar');

Route::get('/cierre-caja', [CierreCajaController::class, 'index'])->name('cierre-caja.index');

Route::get('/apertura-caja', [AperturaCajaController::class, 'index'])->name('apertura-caja.index');
Route::post('/apertura-caja', [AperturaCajaController::class, 'store'])->name('apertura-caja.store');
Route::delete('/apertura-caja/{id}', [AperturaCajaController::class, 'destroy'])->name('apertura-caja.destroy');