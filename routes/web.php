<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/mostrar_lotes', [App\Http\Controllers\LoteController::class, 'mostrar_lotes'])->name('mostrar_lotes');
Route::get('/mostrar_registro', [App\Http\Controllers\LoteController::class, 'mostrar_registro'])->name('mostrar_registro');
Route::post('/registrar_lote', [App\Http\Controllers\LoteController::class, 'registrar_lote'])->name('registrar_lote');
Route::post('/agregar_archivo', [App\Http\Controllers\LoteController::class, 'agregar_archivo'])->name('agregar_archivo');
Route::get('/ver_lote/{id_lote?}', [App\Http\Controllers\LoteController::class, 'ver_lote'])->name('ver_lote');


/***ADMIN***/
Route::get('/mostrar_usuarios', [App\Http\Controllers\LoteController::class, 'mostrar_usuarios'])->name('mostrar_usuarios');

Route::get('/registrar_usuario', [App\Http\Controllers\LoteController::class, 'registrar_usuario'])->name('registrar_usuario');
Route::post('/crear_usuario', [App\Http\Controllers\LoteController::class, 'crear_usuario'])->name('crear_usuario');


