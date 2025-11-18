<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Document Management API routes will be added here
    // For now, we'll use standard resource routes

    // Documents
    Route::get('documents', function () {
        return response()->json(['message' => 'Documents API endpoint']);
    });

    // Document Categories
    Route::get('document-categories', function () {
        return response()->json(['message' => 'Document Categories API endpoint']);
    });
});
