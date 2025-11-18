<?php

use App\Modules\Notifications\Http\Controllers\Api\NotificationController;
use App\Modules\Notifications\Http\Controllers\Api\NotificationTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Notifications
    Route::apiResource('notifications', NotificationController::class);
    Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/{id}/mark-as-unread', [NotificationController::class, 'markAsUnread']);
    Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::get('notifications/unread/count', [NotificationController::class, 'unreadCount']);

    // Notification Templates
    Route::apiResource('notification-templates', NotificationTemplateController::class);
});
