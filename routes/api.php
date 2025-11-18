<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Core\CompanyController;
use App\Http\Controllers\Api\V1\Core\CurrencyController;
use App\Http\Controllers\Api\V1\CRM\LeadController;
use App\Http\Controllers\Api\V1\CRM\OpportunityController;
use App\Http\Controllers\Api\V1\CRM\ContactController;
use App\Http\Controllers\Api\V1\CRM\AccountController;
use App\Http\Controllers\Api\V1\Sales\CustomerController;
use App\Http\Controllers\Api\V1\Sales\QuoteController;
use App\Http\Controllers\Api\V1\Sales\SalesOrderController;
use App\Http\Controllers\Api\V1\Sales\InvoiceController;
use App\Http\Controllers\Api\V1\Purchase\VendorController;
use App\Http\Controllers\Api\V1\Purchase\PurchaseOrderController;
use App\Http\Controllers\Api\V1\Purchase\BillController;
use App\Http\Controllers\Api\V1\Inventory\ProductController;
use App\Http\Controllers\Api\V1\Inventory\ProductCategoryController;
use App\Http\Controllers\Api\V1\Inventory\WarehouseController;
use App\Http\Controllers\Api\V1\Inventory\StockTransferController;
use App\Http\Controllers\Api\V1\HR\EmployeeController;
use App\Http\Controllers\Api\V1\HR\DepartmentController;
use App\Http\Controllers\Api\V1\HR\AttendanceController;
use App\Http\Controllers\Api\V1\HR\LeaveRequestController;
use App\Http\Controllers\Api\V1\Projects\ProjectController;
use App\Http\Controllers\Api\V1\Projects\TaskController;
use App\Http\Controllers\Api\V1\Projects\TimesheetController;
use App\Http\Controllers\Api\V1\Manufacturing\BillOfMaterialsController;
use App\Http\Controllers\Api\V1\Manufacturing\WorkOrderController;
use App\Http\Controllers\Api\V1\Accounting\ChartOfAccountController;
use App\Http\Controllers\Api\V1\Accounting\JournalController;
use App\Http\Controllers\Api\V1\Accounting\JournalEntryController;
use App\Http\Controllers\Api\V1\POS\CashRegisterController;
use App\Http\Controllers\Api\V1\POS\PaymentMethodController;
use App\Http\Controllers\Api\V1\POS\PosSessionController;
use App\Http\Controllers\Api\V1\POS\PosOrderController;
use App\Http\Controllers\Api\V1\POS\PosPaymentController;
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

    // Core module
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('currencies', CurrencyController::class);

    // CRM module
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('opportunities', OpportunityController::class);
    Route::apiResource('contacts', ContactController::class);
    Route::apiResource('accounts', AccountController::class);

    // Sales module
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('quotes', QuoteController::class);
    Route::apiResource('sales-orders', SalesOrderController::class);
    Route::apiResource('invoices', InvoiceController::class);

    // Purchase module
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::apiResource('bills', BillController::class);

    // Inventory module
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('warehouses', WarehouseController::class);
    Route::apiResource('stock-transfers', StockTransferController::class);

    // HR module
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('attendance', AttendanceController::class);
    Route::apiResource('leave-requests', LeaveRequestController::class);

    // Projects module
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('timesheets', TimesheetController::class);

    // Manufacturing module
    Route::apiResource('bill-of-materials', BillOfMaterialsController::class);
    Route::apiResource('work-orders', WorkOrderController::class);

    // Accounting module
    Route::apiResource('chart-of-accounts', ChartOfAccountController::class);
    Route::apiResource('journals', JournalController::class);
    Route::apiResource('journal-entries', JournalEntryController::class);

    // POS module
    Route::apiResource('cash-registers', CashRegisterController::class);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::apiResource('pos-sessions', PosSessionController::class);
    Route::apiResource('pos-orders', PosOrderController::class);
    Route::apiResource('pos-payments', PosPaymentController::class);
});
