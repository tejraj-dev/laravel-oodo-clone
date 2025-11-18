<?php

use App\Modules\Sales\Http\Controllers\Api\CustomerController;
use App\Modules\Sales\Http\Controllers\Api\QuoteController;
use App\Modules\Sales\Http\Controllers\Api\SalesOrderController;
use App\Modules\Sales\Http\Controllers\Api\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sales Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('quotes', QuoteController::class);
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('invoices', InvoiceController::class);
});
