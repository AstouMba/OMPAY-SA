<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SoldeController;
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
    Route::post('/send-otp', [ClientAuthController::class, 'sendOtpActivation']);
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/verify-otp', [ClientAuthController::class, 'verifyOtpNew']);
    Route::middleware('auth:client')->group(function () {
        Route::get('/compte', [ClientController::class, 'compte'])->name('client.compte');
        Route::get('/{numero}/solde', [SoldeController::class, 'show'])->name('client.solde');
        Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::post('/transactions/payment', [TransactionController::class, 'payment'])->name('transactions.payment');
        Route::post('/transactions/transfert', [TransactionController::class, 'transfert'])->name('transactions.transfert');
    });
});

