<?php

use App\Modules\Accounting\Http\Controllers\Api\ChartOfAccountController;
use App\Modules\Accounting\Http\Controllers\Api\JournalController;
use App\Modules\Accounting\Http\Controllers\Api\JournalEntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accounting Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('chart-of-accounts', ChartOfAccountController::class);
    Route::apiResource('journals', JournalController::class);
    Route::apiResource('journal-entries', JournalEntryController::class);
});
