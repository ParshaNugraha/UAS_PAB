<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\WebhookController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/lelangs/aktif', [LelangController::class, 'index']);
    Route::get('/lelangs/barang{id}', [LelangController::class, 'show']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/lelangs/bikin', [LelangController::class, 'store']);
    Route::put('/lelangs/update{id}', [LelangController::class, 'update']);
    Route::delete('/lelangs/hapus{id}', [LelangController::class, 'destroy']);
    Route::post('/lelangs/berakhir', [WebhookController::class, 'notify']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



