<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WhatsappController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'auth'
], function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::group([
        'middleware' => 'auth:api'
    ], function(){
        Route::get('data', [AuthController::class, 'data']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('displayTodo', [TodoController::class, 'displayTodo']);
        Route::post('createTodo', [TodoController::class, 'createTodo']);
        Route::post('updateTodo/{id}', [TodoController::class, 'updateTodo']);
        Route::post('deleteTodo/{id}', [TodoController::class, 'deleteTodo']);
        Route::get('displayPelanggan', [PelangganController::class, 'displayPelanggan']);
        Route::post('createPelanggan', [PelangganController::class, 'createPelanggan']);
        Route::get('getPelanggan/{id}', [PelangganController::class, 'byId']);
        Route::post('updatePelanggan/{id}', [PelangganController::class, 'updatePelanggan']);
        Route::post('deletePelanggan/{id}', [PelangganController::class, 'deletePelanggan']);
        Route::get('displayTransaksi', [TransaksiController::class, 'displayTransaksi']);
        Route::post('createTransaksi', [TransaksiController::class, 'createTransaksi']);
        Route::get('getTransaksi/{id}', [TransaksiController::class, 'byId']);
        Route::post('updateTransaksi/{id}', [TransaksiController::class, 'updateTransaksi']);
        Route::post('deleteTransaksi/{id}', [TransaksiController::class, 'deleteTransaksi']);
        Route::get('/send', [EmailController::class,'index']);
        Route::post('/send', [EmailController::class,'index']);
        Route::post('/whatsapp', [WhatsappController::class, 'store']);
    	// Route::post('/send/mail/feedback', [EmailController::class, 'send_feedback']);
    });
});
