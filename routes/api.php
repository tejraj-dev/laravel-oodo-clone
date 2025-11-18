<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/revoke-all', [AuthController::class, 'revokeAll']);
});

// Load module routes
$modules = ['Core', 'CRM', 'Sales', 'Purchase', 'Inventory', 'HR', 'Projects', 'Manufacturing', 'Accounting', 'POS', 'Reporting', 'Notifications'];

foreach ($modules as $module) {
    $moduleApiRoutes = app_path("Modules/{$module}/routes/api.php");
    if (file_exists($moduleApiRoutes)) {
        require $moduleApiRoutes;
    }
}
