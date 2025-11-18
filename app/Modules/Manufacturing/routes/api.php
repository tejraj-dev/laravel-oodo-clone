<?php

use App\Modules\Manufacturing\Http\Controllers\Api\BillOfMaterialsController;
use App\Modules\Manufacturing\Http\Controllers\Api\WorkOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manufacturing Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('bill-of-materials', BillOfMaterialsController::class);
    Route::apiResource('work-orders', WorkOrderController::class);
});
