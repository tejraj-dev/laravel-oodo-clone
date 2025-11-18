<?php

/**
 * API Resource and Controller Generator for Laravel ERP
 */

$modules = [
    'Core' => ['Company', 'Currency'],
    'CRM' => ['Lead', 'Opportunity', 'Contact', 'Account'],
    'Sales' => ['Customer', 'Quote', 'SalesOrder', 'Invoice'],
    'Purchase' => ['Vendor', 'PurchaseOrder', 'Bill'],
    'Inventory' => ['Product', 'ProductCategory', 'Warehouse', 'StockTransfer'],
    'HR' => ['Employee', 'Department', 'Attendance', 'LeaveRequest'],
    'Projects' => ['Project', 'Task', 'Timesheet'],
    'Manufacturing' => ['BillOfMaterials', 'WorkOrder'],
    'Accounting' => ['ChartOfAccount', 'Journal', 'JournalEntry'],
    'POS' => ['CashRegister', 'PaymentMethod', 'PosSession', 'PosOrder', 'PosPayment'],
];

function createApiResource($module, $model) {
    $resourceDir = __DIR__ . "/app/Http/Resources/V1/{$module}";
    if (!is_dir($resourceDir)) {
        mkdir($resourceDir, 0755, true);
    }

    $content = "<?php

namespace App\\Http\\Resources\\V1\\{$module};

use Illuminate\\Http\\Request;
use Illuminate\\Http\\Resources\\Json\\JsonResource;

class {$model}Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request \$request): array
    {
        return [
            'id' => \$this->id,
            'created_at' => \$this->created_at,
            'updated_at' => \$this->updated_at,
            // Add more fields as needed
        ];
    }
}
";

    file_put_contents("{$resourceDir}/{$model}Resource.php", $content);
    echo "Created {$model}Resource.php\n";
}

function createApiController($module, $model) {
    $controllerDir = __DIR__ . "/app/Http/Controllers/Api/V1/{$module}";
    if (!is_dir($controllerDir)) {
        mkdir($controllerDir, 0755, true);
    }

    $modelClass = "App\\Modules\\{$module}\\Models\\{$model}";
    $resourceClass = "App\\Http\\Resources\\V1\\{$module}\\{$model}Resource";

    $content = "<?php

namespace App\\Http\\Controllers\\Api\\V1\\{$module};

use {$modelClass};
use {$resourceClass};
use App\\Http\\Controllers\\Api\\V1\\ApiController;
use Illuminate\\Http\\JsonResponse;
use Illuminate\\Http\\Request;

class {$model}Controller extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request \$request): JsonResponse
    {
        \$perPage = \$request->input('per_page', 15);
        \$query = {$model}::query();

        // Add company scoping if model has company_id
        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        // Add search if provided
        if (\$search = \$request->input('search')) {
            \$query->where(function(\$q) use (\$search) {
                \$q->where('name', 'like', \"%{\$search}%\");
                // Add more searchable fields as needed
            });
        }

        \$data = \$query->paginate(\$perPage);

        return \$this->paginatedResponse(\$data, {$model}Resource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request \$request): JsonResponse
    {
        \$validated = \$request->validate([
            // Add validation rules
        ]);

        // Add company_id if model has it
        if (method_exists({$model}::class, 'company')) {
            \$validated['company_id'] = \$request->user()->company_id;
        }

        \$record = {$model}::create(\$validated);

        return \$this->successResponse(
            new {$model}Resource(\$record),
            '{$model} created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request \$request, string \$id): JsonResponse
    {
        \$query = {$model}::query();

        // Add company scoping if model has company_id
        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        \$record = \$query->findOrFail(\$id);

        return \$this->successResponse(new {$model}Resource(\$record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request \$request, string \$id): JsonResponse
    {
        \$query = {$model}::query();

        // Add company scoping if model has company_id
        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        \$record = \$query->findOrFail(\$id);

        \$validated = \$request->validate([
            // Add validation rules
        ]);

        \$record->update(\$validated);

        return \$this->successResponse(
            new {$model}Resource(\$record),
            '{$model} updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request \$request, string \$id): JsonResponse
    {
        \$query = {$model}::query();

        // Add company scoping if model has company_id
        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        \$record = \$query->findOrFail(\$id);
        \$record->delete();

        return \$this->successResponse(null, '{$model} deleted successfully');
    }
}
";

    file_put_contents("{$controllerDir}/{$model}Controller.php", $content);
    echo "Created {$model}Controller.php\n";
}

// Generate all API resources and controllers
echo "Generating API Resources and Controllers...\n\n";

foreach ($modules as $module => $models) {
    echo "Generating {$module} module API...\n";
    foreach ($models as $model) {
        createApiResource($module, $model);
        createApiController($module, $model);
    }
    echo "\n";
}

echo "All API resources and controllers generated successfully!\n";
