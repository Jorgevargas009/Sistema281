<?php

use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\rolecontroller;
use App\Http\Controllers\userController;
use App\Mail\ConfirmacionMailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DetalleController;
use App\Http\Controllers\DireccioneController;
use App\Http\Controllers\inicioController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ResenaController;
use App\Models\Detalle_compra;use App\Http\Controllers\DashboardController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas para verificación de correo
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');
Route::get('email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');

Route::get('/panel/index', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

// Ruta para mostrar el formulario de verificación
Route::get('/verify', [loginController::class, 'showVerificationForm'])->name('verify');

Route::get('/notificaciones/{id}/marcar-como-leida', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcarComoLeida');
// Ruta correcta para la comunidad
Route::get('/pedidos/comunidad', [PedidoController::class, 'comunidad'])->name('pedidos.comunidad');
Route::get('/pedidos/my', [PedidoController::class, 'my'])->name('pedidos.my');
Route::get('/pedidos/delivery', [PedidoController::class, 'delivery'])->name('pedidos.delivery');
Route::post('/reseñas/guardar', [ResenaController::class, 'guardar'])->name('reseñas.guardar');
Route::post('/reseñas/store', [ResenaController::class, 'store'])->name('reseñas.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos/all', [PedidoController::class, 'all'])->name('pedidos.all');
    Route::post('/pedidos/aceptar/{id}', [PedidoController::class, 'aceptar'])->name('pedidos.aceptar');
});
// Ruta para procesar el código de verificación ingresado
Route::post('/verify', [loginController::class, 'verifyCode'])->name('verify.code');
// Ruta para la página principal
Route::get("/", [homeController::class, 'index'])->name('panel');

// Ruta para la página de inicio
Route::get('/inicio', [inicioController::class, 'inicio']);

// Rutas de autenticación con verificación de correo habilitada
Auth::routes(['verify' => true]);

// Protege las rutas que requieren que el usuario esté verificado
//Route::middleware(['auth', 'verified'])->group(function () {
Route::resources([
    'productos' => ProductoController::class,
    'users' => userController::class,
    'roles' => rolecontroller::class,
    'detalle_compras' => DetalleController::class,
    'pagos' => PagoController::class,
    'pedidos' => PedidoController::class,
]);

Route::post('/pedidos/recibido/{pedido}', [PedidoController::class, 'confirmarRecepcion'])->name('pedidos.recibido');
// Ejemplo de ruta en web.php
Route::get('/pedido/confirmar/{id}', [PedidoController::class, 'confirmarRecepcion'])->name('pedido.confirmar');

Route::get('/Pedido/index', [PedidoController::class, 'index'])->name('pedidos.index')->middleware('auth');
Route::post('/direcciones/guardar', [DireccioneController::class, 'guardar'])->name('direcciones.guardar');
Route::post('/direcciones/asociar/{pedido}', [DireccioneController::class, 'asociarDireccion'])->name('direcciones.asociar');

Route::post('/detalle_compras/store/{producto}', [DetalleController::class, 'store'])->name('detalle_compras.store');

// Vista de categorías protegida por autenticación y verificación
Route::view('/categorias', 'categoria.index');
//});
Route::get('/detalle_compra/index', [DetalleController::class, 'index'])->name('detalle_compras.index');
Route::put('/detalle_compras/{detalle_compra}', [DetalleController::class, 'update'])->name('detalle_compras.update');
// Rutas de registro y login
Route::get('/register', [userController::class, 'register'])->name('register');

Route::get('/login', [loginController::class, 'index'])->name('login');
Route::post('/login', [loginController::class, 'login']);
Route::get('/logout', [logoutController::class, 'logout'])->name('logout');

Route::get('/401', function () {
    return view('pages.401');
});

// Ruta para listar todos los pagos
Route::get('/Pago/index', [PagoController::class, 'index'])->name('Pago.index');

// Ruta para confirmar un pago
Route::post('/pagos/confirmar/{id}', [PagoController::class, 'confirmarPago'])->name('pagos.confirmar');
Route::post('/pagos/store', [PagoController::class, 'store'])->name('pagos.store');

Route::post('/pagos/{id}/aprobar', [PagoController::class, 'aprobarPago'])->name('pagos.aprobar');
Route::post('/pagos/{id}/rechazar', [PagoController::class, 'rechazarPago'])->name('pagos.rechazar');


Route::get('/404', function () {
    return view('pages.404');
});


Route::get('/500', function () {
    return view('pages.500');
});

Route::get('/producto/all', [ProductoController::class, 'allProducts'])->name('productos.all');
Route::get('/producto/ventas', [ProductoController::class, 'ventas'])->name('productos.ventas');

Route::get('/producto/comunidad', [ProductoController::class, 'ProductoComunidad'])->name('productos.comunidad');