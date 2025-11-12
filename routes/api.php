<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\TransactionFeeController;

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
        Route::post('/clients', [AdminClientController::class, 'create']);
        Route::get('/transaction-fees', [TransactionFeeController::class, 'index']);
        Route::put('/transaction-fees/{type}', [TransactionFeeController::class, 'updateByType']);
        Route::post('/transaction-fees/calculate', [TransactionFeeController::class, 'calculate']);
    });
});

// Client Authentication Routes
Route::prefix('v1/client')->group(function () {
    Route::post('/send-otp', [ClientAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [ClientAuthController::class, 'verifyOtp']);
    // Route::middleware('auth:client')->group(function () {
        Route::post('/logout', [ClientAuthController::class, 'logout']);
        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::post('/transactions/payment', [TransactionController::class, 'payment']);
        Route::post('/transactions/transfert', [TransactionController::class, 'transfert']);
    });
