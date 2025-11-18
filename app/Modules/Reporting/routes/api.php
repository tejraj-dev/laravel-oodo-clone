<?php

use App\Modules\Reporting\Http\Controllers\Api\ReportController;
use App\Modules\Reporting\Http\Controllers\Api\DashboardController;
use App\Modules\Reporting\Http\Controllers\Api\KPIController;
use App\Modules\Reporting\Http\Controllers\Api\ReportTemplateController;
use App\Modules\Reporting\Http\Controllers\Api\ScheduledReportController;
use App\Modules\Reporting\Http\Controllers\Api\WidgetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reporting Module API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('reports', ReportController::class);
    Route::apiResource('dashboards', DashboardController::class);
    Route::apiResource('kpis', KPIController::class);
    Route::apiResource('report-templates', ReportTemplateController::class);
    Route::apiResource('scheduled-reports', ScheduledReportController::class);
    Route::apiResource('widgets', WidgetController::class);
});
