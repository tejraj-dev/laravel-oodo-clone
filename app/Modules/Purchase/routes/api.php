<?php

use App\Modules\Purchase\Http\Controllers\Api\VendorController;
use App\Modules\Purchase\Http\Controllers\Api\PurchaseOrderController;
use App\Modules\Purchase\Http\Controllers\Api\BillController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Purchase Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('bills', BillController::class);
});
