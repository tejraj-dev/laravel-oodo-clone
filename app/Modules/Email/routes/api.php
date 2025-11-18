<?php

use App\Modules\Email\Http\Controllers\Api\EmailTemplateController;
use App\Modules\Email\Http\Controllers\Api\EmailCampaignController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Email Templates
    Route::apiResource('email-templates', EmailTemplateController::class);

    // Email Campaigns
    Route::apiResource('email-campaigns', EmailCampaignController::class);
    Route::get('email-campaigns/{id}/statistics', [EmailCampaignController::class, 'statistics']);
});
