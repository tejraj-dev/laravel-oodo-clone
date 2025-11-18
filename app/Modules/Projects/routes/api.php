<?php

use App\Modules\Projects\Http\Controllers\Api\ProjectController;
use App\Modules\Projects\Http\Controllers\Api\TaskController;
use App\Modules\Projects\Http\Controllers\Api\TimesheetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Projects Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('timesheets', TimesheetController::class);
});
