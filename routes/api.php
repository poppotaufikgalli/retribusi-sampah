<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

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

Route::post('/login', [ApiController::class, 'api_login']);
Route::middleware('auth:sanctum')->group(function () {
    
    Route::match(['get', 'post'],'/getWajibRetribusi', [ApiController::class, 'getWajibRetribusi']);
    Route::get('/getWajibRetribusi2', [ApiController::class, 'getWajibRetribusi2']);
    Route::get('/getWajibRetribusiInsidentil', [ApiController::class, 'getWajibRetribusiInsidentil']);
    Route::match(['get', 'post'],'/getKarcis2', [ApiController::class, 'getKarcis2']);
    Route::match(['get', 'post'],'/getTagihan', [ApiController::class, 'getTagihan']);
    Route::post('/pembayaran', [ApiController::class, 'pembayaran']);
    Route::post('/pembayaran2', [ApiController::class, 'pembayaran2']);
    Route::post('/pembayaran/store', [ApiController::class, 'pembayaran_store']);
    Route::post('/getLogKunjungan', [ApiController::class, 'getLogKunjungan']);
    Route::post('/kunjungan/store', [ApiController::class, 'kunjungan_store']);
    Route::post('/getRekapTransaksi', [ApiController::class, 'getRekapTransaksi']);
    Route::get('/getUserInfo', [ApiController::class, 'getUserInfo']);

    Route::post('/device/update', [ApiController::class, 'device_update']);
});

Route::post('/getTransaksi', [ApiController::class, 'getTransaksi']);
Route::get('/getJnsKeteranganKunjungan', [ApiController::class, 'getJnsKeteranganKunjungan']);
//Route::get('/getRekapTransaksi/{id}', [ApiController::class, 'getRekapTransaksi']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/checkConnection', function () {
    return response()->json(['ok' => true], 200);
});

Route::get('/getPemilik', [ApiController::class, 'getPemilik'])->name('api.getPemilik');

Route::get('/getWajibPajak', [ApiController::class, 'getWajibPajak'])->name('api.getWajibPajak');
Route::get('/getKarcis/{id_registrasi_karcis?}', [ApiController::class, 'getKarcis'])->name('api.getKarcis');
//Route::get('/getTagihan/{id_registrasi_karcis?}', [ApiController::class, 'getTagihan'])->name('api.getTagihan');


//Route::get('/getListRegisterKarcisTagihan/{id_registrasi_karcis?}', [ApiController::class, 'getListRegisterKarcisTagihan'])->name('api.getListRegisterKarcisTagihan');