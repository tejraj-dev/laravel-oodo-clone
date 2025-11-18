<?php

/**
 * Generate API routes for each module
 */

$moduleRoutes = [
    'CRM' => [
        'leads' => 'LeadController',
        'opportunities' => 'OpportunityController',
        'contacts' => 'ContactController',
        'accounts' => 'AccountController',
    ],
    'Sales' => [
        'customers' => 'CustomerController',
        'quotes' => 'QuoteController',
        'sales-orders' => 'SalesOrderController',
        'invoices' => 'InvoiceController',
    ],
    'Purchase' => [
        'vendors' => 'VendorController',
        'purchase-orders' => 'PurchaseOrderController',
        'bills' => 'BillController',
    ],
    'Inventory' => [
        'products' => 'ProductController',
        'product-categories' => 'ProductCategoryController',
        'warehouses' => 'WarehouseController',
        'stock-transfers' => 'StockTransferController',
    ],
    'HR' => [
        'employees' => 'EmployeeController',
        'departments' => 'DepartmentController',
        'attendance' => 'AttendanceController',
        'leave-requests' => 'LeaveRequestController',
    ],
    'Projects' => [
        'projects' => 'ProjectController',
        'tasks' => 'TaskController',
        'timesheets' => 'TimesheetController',
    ],
    'Manufacturing' => [
        'bill-of-materials' => 'BillOfMaterialsController',
        'work-orders' => 'WorkOrderController',
    ],
    'Accounting' => [
        'chart-of-accounts' => 'ChartOfAccountController',
        'journals' => 'JournalController',
        'journal-entries' => 'JournalEntryController',
    ],
    'POS' => [
        'cash-registers' => 'CashRegisterController',
        'payment-methods' => 'PaymentMethodController',
        'pos-sessions' => 'PosSessionController',
        'pos-orders' => 'PosOrderController',
        'pos-payments' => 'PosPaymentController',
    ],
];

foreach ($moduleRoutes as $module => $routes) {
    $routeDir = __DIR__ . "/app/Modules/{$module}/routes";
    if (!is_dir($routeDir)) {
        mkdir($routeDir, 0755, true);
    }

    $content = "<?php\n\n";

    // Add use statements
    foreach ($routes as $controller) {
        $content .= "use App\\Modules\\{$module}\\Http\\Controllers\\Api\\{$controller};\n";
    }

    $content .= "use Illuminate\\Support\\Facades\\Route;\n\n";
    $content .= "/*\n";
    $content .= "|--------------------------------------------------------------------------\n";
    $content .= "| {$module} Module API Routes\n";
    $content .= "|--------------------------------------------------------------------------\n";
    $content .= "*/\n\n";
    $content .= "Route::prefix('v1')->middleware('auth:sanctum')->group(function () {\n";

    foreach ($routes as $route => $controller) {
        $controllerVar = str_replace('Controller', '', $controller);
        $content .= "    Route::apiResource('{$route}', {$controller}::class);\n";
    }

    $content .= "});\n";

    file_put_contents("{$routeDir}/api.php", $content);
    echo "Created routes for {$module} module\n";
}

echo "\nAll module routes created successfully!\n";
