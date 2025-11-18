<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Import Templates
    Route::get('import-templates', function () {
        return response()->json(['message' => 'Import Templates API endpoint']);
    });

    // Import Jobs
    Route::get('import-jobs', function () {
        return response()->json(['message' => 'Import Jobs API endpoint']);
    });

    Route::post('import-jobs', function () {
        return response()->json(['message' => 'Create import job endpoint']);
    });

    // Export Jobs
    Route::get('export-jobs', function () {
        return response()->json(['message' => 'Export Jobs API endpoint']);
    });

    Route::post('export-jobs', function () {
        return response()->json(['message' => 'Create export job endpoint']);
    });

    Route::get('export-jobs/{id}/download', function ($id) {
        return response()->json(['message' => 'Download export file endpoint']);
    });
});
