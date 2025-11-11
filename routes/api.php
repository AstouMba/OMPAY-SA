<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoldeController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// Admin Authentication Routes
Route::prefix('v1/admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/clients', [AdminClientController::class, 'create']);
    });
});

// Client Authentication Routes
Route::prefix('v1/client')->group(function () {
    Route::post('/send-otp', [ClientAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [ClientAuthController::class, 'verifyOtp']);
    Route::middleware('auth:client')->group(function () {
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::get('/user', [ClientAuthController::class, 'user']);
        Route::get('/solde', [SoldeController::class, 'getSolde']);
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/qrcode', [ClientController::class, 'getQrCode']);
    });
});
