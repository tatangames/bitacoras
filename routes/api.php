<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiNotificacion;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('prueba-noti',  [ApiNotificacion::class, 'enviarNotificacion']);
Route::get('lista/ticket-pendientes',  [ApiNotificacion::class, 'listaTicketPendientes']);

