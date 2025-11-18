<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Approval Workflows
    Route::get('approval-workflows', function () {
        return response()->json(['message' => 'Approval Workflows API endpoint']);
    });

    // Approval Requests
    Route::get('approval-requests', function () {
        return response()->json(['message' => 'Approval Requests API endpoint']);
    });

    // Approval actions
    Route::post('approval-requests/{id}/approve', function ($id) {
        return response()->json(['message' => 'Approve request endpoint']);
    });

    Route::post('approval-requests/{id}/reject', function ($id) {
        return response()->json(['message' => 'Reject request endpoint']);
    });
});
