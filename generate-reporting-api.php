<?php

/**
 * Generate REST API for Reporting Module
 */

$models = ['Report', 'Dashboard', 'KPI', 'ReportTemplate', 'ScheduledReport', 'Widget'];

// Generate API Resources
foreach ($models as $model) {
    $resourceDir = __DIR__ . "/app/Modules/Reporting/Http/Resources";

    $content = "<?php

namespace App\\Modules\\Reporting\\Http\\Resources;

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
            'name' => \$this->name,
            'created_at' => \$this->created_at,
            'updated_at' => \$this->updated_at,
        ];
    }
}
";

    file_put_contents("{$resourceDir}/{$model}Resource.php", $content);
    echo "Created {$model}Resource.php\n";
}

// Generate API Controllers
foreach ($models as $model) {
    $controllerDir = __DIR__ . "/app/Modules/Reporting/Http/Controllers/Api";

    $content = "<?php

namespace App\\Modules\\Reporting\\Http\\Controllers\\Api;

use App\\Modules\\Reporting\\Models\\{$model};
use App\\Modules\\Reporting\\Http\\Resources\\{$model}Resource;
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

        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        if (\$search = \$request->input('search')) {
            \$query->where(function(\$q) use (\$search) {
                \$q->where('name', 'like', \"%{\$search}%\");
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
            'name' => 'required|max:255',
        ]);

        if (method_exists({$model}::class, 'company')) {
            \$validated['company_id'] = \$request->user()->company_id;
        }

        \$validated['user_id'] = \$request->user()->id;

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

        if (method_exists({$model}::class, 'company')) {
            \$query->where('company_id', \$request->user()->company_id);
        }

        \$record = \$query->findOrFail(\$id);

        \$validated = \$request->validate([
            'name' => 'sometimes|max:255',
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

// Generate routes
$routesDir = __DIR__ . "/app/Modules/Reporting/routes";
$routesContent = "<?php

use App\\Modules\\Reporting\\Http\\Controllers\\Api\\ReportController;
use App\\Modules\\Reporting\\Http\\Controllers\\Api\\DashboardController;
use App\\Modules\\Reporting\\Http\\Controllers\\Api\\KPIController;
use App\\Modules\\Reporting\\Http\\Controllers\\Api\\ReportTemplateController;
use App\\Modules\\Reporting\\Http\\Controllers\\Api\\ScheduledReportController;
use App\\Modules\\Reporting\\Http\\Controllers\\Api\\WidgetController;
use Illuminate\\Support\\Facades\\Route;

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
";

file_put_contents("{$routesDir}/api.php", $routesContent);
echo "Created Reporting module API routes\n";

echo "\nAll Reporting API files generated successfully!\n";
