<?php

use App\Http\Controllers\homeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\rolecontroller;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

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

Route::get("/", [homeController::class,'index'])->name('panel');



Route::resources([
    'productos'=> ProductoController::class,
    'users'=> userController::class,
    'roles'=> rolecontroller::class,
]);

Route::view('/categorias','categoria.index');

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
