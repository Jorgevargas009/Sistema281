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
use App\Http\Controllers\inicioController;

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

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');
    
Route::get("/", [homeController::class,'index'])->name('panel');

Route::get('/inicio', [inicioController::class, 'inicio']);

//Auth::routes(['verify' => true]);

Route::resources([
    'productos'=> ProductoController::class,
    'users'=> userController::class,
    'roles'=> rolecontroller::class,
]);

Route::get('/producto/all', [ProductoController::class, 'allProducts'])->name('productos.all');


Route::view('/categorias','categoria.index');
Route::get('/register', [userController::class,'register'])->name('register');

Route::get('/login', [loginController::class,'index'])->name('login');
Route::post('/login', [loginController::class,'login']);
Route::get('/logout', [logoutController::class,'logout'])->name('logout');

Route::get('/401', function () {
    return view('pages.401');
});


Route::get('/404', function () {
    return view('pages.404');
});


Route::get('/500', function () {
    return view('pages.500');
});

Route::get('confirmacion', function () {
    Mail::to(('danny.dxs.killer@gmail.com'))->send(new ConfirmacionMailable());
    return "mensaje enviado";
});
