<?php

use App\Modules\Core\Http\Controllers\Api\CompanyController;
use App\Modules\Core\Http\Controllers\Api\CurrencyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Core Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('currencies', CurrencyController::class);
});
