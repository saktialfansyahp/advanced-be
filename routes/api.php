<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CommandController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;

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
    Route::post('command', [CommandController::class, 'runCommand']);
    Route::get('/dom', [EmailController::class,'sendInvoice']);
    Route::get('artisan', [ReminderController::class, 'reminder']);
    Route::get('status/{name}', [StatusController::class, 'byName']);
    Route::get('getStatus', [StatusController::class, 'index']);
    Route::post('status', [StatusController::class, 'store']);
    Route::post('updateStatus', [StatusController::class, 'update']);
    Route::group([
        'middleware' => 'auth:api'
    ], function(){
        Route::get('data', [AuthController::class, 'data']);
        Route::get('dataUser', [AuthController::class, 'dataUser']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('destroy/{id}', [AuthController::class, 'destroy']);
        Route::post('update/{id}', [AuthController::class, 'update']);
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
        Route::get('getProduk', [ProdukController::class, 'index']);
        Route::post('createProduk', [ProdukController::class, 'create']);
        Route::get('getProduk/{id}', [ProdukController::class, 'getById']);
        Route::post('updateProduk/{id}', [ProdukController::class, 'update']);
        Route::post('deleteProduk/{id}', [ProdukController::class, 'destroy']);
        Route::post('/pdf', [EmailController::class,'pdf']);
        Route::post('/send', [EmailController::class,'index']);
        Route::post('/whatsapp', [WhatsappController::class, 'store']);
        Route::post('/lunas', [WhatsappController::class, 'lunas']);
    	// Route::post('/send/mail/feedback', [EmailController::class, 'send_feedback']);
    });
});
