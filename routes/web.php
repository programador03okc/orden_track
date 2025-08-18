<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ChatbotController;
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

Route::get('/', [OrdenController::class, 'index'])->name('home');
Route::get('/guia/ver/{idOrden}', [OrdenController::class, 'descargarGuia'])->name('guia.ver');
Route::post('/chatbot', [ChatbotController::class, 'handle']);