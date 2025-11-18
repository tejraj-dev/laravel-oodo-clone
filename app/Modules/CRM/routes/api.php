<?php

use App\Modules\CRM\Http\Controllers\Api\LeadController;
use App\Modules\CRM\Http\Controllers\Api\OpportunityController;
use App\Modules\CRM\Http\Controllers\Api\ContactController;
use App\Modules\CRM\Http\Controllers\Api\AccountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CRM Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('opportunities', OpportunityController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('accounts', AccountController::class);
});
