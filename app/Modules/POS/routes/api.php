<?php

use App\Modules\POS\Http\Controllers\Api\CashRegisterController;
use App\Modules\POS\Http\Controllers\Api\PaymentMethodController;
use App\Modules\POS\Http\Controllers\Api\PosSessionController;
use App\Modules\POS\Http\Controllers\Api\PosOrderController;
use App\Modules\POS\Http\Controllers\Api\PosPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| POS Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('cash-registers', CashRegisterController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('pos-sessions', PosSessionController::class);
    Route::apiResource('pos-orders', PosOrderController::class);
    Route::apiResource('pos-payments', PosPaymentController::class);
});
