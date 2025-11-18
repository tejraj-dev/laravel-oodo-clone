<?php

use App\Modules\Inventory\Http\Controllers\Api\ProductController;
use App\Modules\Inventory\Http\Controllers\Api\ProductCategoryController;
use App\Modules\Inventory\Http\Controllers\Api\WarehouseController;
use App\Modules\Inventory\Http\Controllers\Api\StockTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Inventory Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('stock-transfers', StockTransferController::class);
});
