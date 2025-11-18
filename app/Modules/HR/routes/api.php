<?php

use App\Modules\HR\Http\Controllers\Api\EmployeeController;
use App\Modules\HR\Http\Controllers\Api\DepartmentController;
use App\Modules\HR\Http\Controllers\Api\AttendanceController;
use App\Modules\HR\Http\Controllers\Api\LeaveRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HR Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('attendance', AttendanceController::class);
    Route::apiResource('leave-requests', LeaveRequestController::class);
});
