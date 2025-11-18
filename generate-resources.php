<?php

/**
 * Filament Resource Generator for Laravel ERP
 * This script generates all Filament resources for the ERP modules
 */

$resources = [
    // CRM Module
    'CRM' => [
        'Opportunity' => ['icon' => 'heroicon-o-chart-bar', 'sort' => 2],
        'Contact' => ['icon' => 'heroicon-o-user', 'sort' => 3],
        'Account' => ['icon' => 'heroicon-o-building-office', 'sort' => 4],
    ],
    // Sales Module
    'Sales' => [
        'Customer' => ['icon' => 'heroicon-o-user-group', 'sort' => 1],
        'Quote' => ['icon' => 'heroicon-o-document-text', 'sort' => 2],
        'SalesOrder' => ['icon' => 'heroicon-o-shopping-cart', 'sort' => 3],
        'Invoice' => ['icon' => 'heroicon-o-document-duplicate', 'sort' => 4],
    ],
    // Purchase Module
    'Purchase' => [
        'Vendor' => ['icon' => 'heroicon-o-building-storefront', 'sort' => 1],
        'PurchaseOrder' => ['icon' => 'heroicon-o-shopping-bag', 'sort' => 2],
        'Bill' => ['icon' => 'heroicon-o-receipt-percent', 'sort' => 3],
    ],
    // Inventory Module
    'Inventory' => [
        'Product' => ['icon' => 'heroicon-o-cube', 'sort' => 1],
        'ProductCategory' => ['icon' => 'heroicon-o-tag', 'sort' => 2],
        'Warehouse' => ['icon' => 'heroicon-o-building-library', 'sort' => 3],
        'StockTransfer' => ['icon' => 'heroicon-o-arrow-path', 'sort' => 4],
    ],
    // HR Module
    'HR' => [
        'Employee' => ['icon' => 'heroicon-o-identification', 'sort' => 1],
        'Department' => ['icon' => 'heroicon-o-building-office-2', 'sort' => 2],
        'Attendance' => ['icon' => 'heroicon-o-clock', 'sort' => 3],
        'LeaveRequest' => ['icon' => 'heroicon-o-calendar-days', 'sort' => 4],
    ],
    // Projects Module
    'Projects' => [
        'Project' => ['icon' => 'heroicon-o-folder', 'sort' => 1],
        'Task' => ['icon' => 'heroicon-o-clipboard-document-check', 'sort' => 2],
        'Timesheet' => ['icon' => 'heroicon-o-clock', 'sort' => 3],
    ],
    // Manufacturing Module
    'Manufacturing' => [
        'BillOfMaterials' => ['icon' => 'heroicon-o-queue-list', 'sort' => 1],
        'WorkOrder' => ['icon' => 'heroicon-o-wrench-screwdriver', 'sort' => 2],
    ],
    // Accounting Module
    'Accounting' => [
        'ChartOfAccount' => ['icon' => 'heroicon-o-numbered-list', 'sort' => 1],
        'Journal' => ['icon' => 'heroicon-o-book-open', 'sort' => 2],
        'JournalEntry' => ['icon' => 'heroicon-o-pencil-square', 'sort' => 3],
    ],
];

function createResourceFile($module, $model, $icon, $sort) {
    $modelPlural = $model . 's';
    if ($model === 'ChartOfAccount') $modelPlural = 'ChartOfAccounts';
    if ($model === 'BillOfMaterials') $modelPlural = 'BillOfMaterials';

    $content = "<?php

namespace App\\Modules\\{$module}\\Filament\\Resources;

use App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource\\Pages;
use App\\Modules\\{$module}\\Models\\{$model};
use Filament\\Forms;
use Filament\\Forms\\Form;
use Filament\\Resources\\Resource;
use Filament\\Tables;
use Filament\\Tables\\Table;

class {$model}Resource extends Resource
{
    protected static ?string \$model = {$model}::class;

    protected static ?string \$navigationIcon = '{$icon}';

    protected static ?string \$navigationGroup = '{$module}';

    protected static ?int \$navigationSort = {$sort};

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                // Add form fields here
                Forms\\Components\\Section::make('Details')
                    ->schema([
                        Forms\\Components\\TextInput::make('name')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                Tables\\Columns\\TextColumn::make('id')
                    ->sortable(),
                Tables\\Columns\\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\\Actions\\EditAction::make(),
                Tables\\Actions\\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\\Actions\\BulkActionGroup::make([
                    Tables\\Actions\\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\\List{$modelPlural}::route('/'),
            'create' => Pages\\Create{$model}::route('/create'),
            'edit' => Pages\\Edit{$model}::route('/{record}/edit'),
        ];
    }
}
";

    $dir = __DIR__ . "/app/Modules/{$module}/Filament/Resources";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents("{$dir}/{$model}Resource.php", $content);
    echo "Created {$model}Resource.php\n";

    // Create Pages
    createPages($module, $model, $modelPlural);
}

function createPages($module, $model, $modelPlural) {
    $pagesDir = __DIR__ . "/app/Modules/{$module}/Filament/Resources/{$model}Resource/Pages";
    if (!is_dir($pagesDir)) {
        mkdir($pagesDir, 0755, true);
    }

    // List Page
    $listContent = "<?php

namespace App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource;
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class List{$modelPlural} extends ListRecords
{
    protected static string \$resource = {$model}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\CreateAction::make(),
        ];
    }
}
";
    file_put_contents("{$pagesDir}/List{$modelPlural}.php", $listContent);

    // Create Page
    $createContent = "<?php

namespace App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$model} extends CreateRecord
{
    protected static string \$resource = {$model}Resource::class;
}
";
    file_put_contents("{$pagesDir}/Create{$model}.php", $createContent);

    // Edit Page
    $editContent = "<?php

namespace App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\{$module}\\Filament\\Resources\\{$model}Resource;
use Filament\\Actions;
use Filament\\Resources\\Pages\\EditRecord;

class Edit{$model} extends EditRecord
{
    protected static string \$resource = {$model}Resource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\\DeleteAction::make(),
        ];
    }
}
";
    file_put_contents("{$pagesDir}/Edit{$model}.php", $editContent);

    echo "Created pages for {$model}\n";
}

// Generate all resources
echo "Generating Filament Resources...\n\n";

foreach ($resources as $module => $models) {
    echo "Generating {$module} module resources...\n";
    foreach ($models as $model => $config) {
        createResourceFile($module, $model, $config['icon'], $config['sort']);
    }
    echo "\n";
}

echo "All resources generated successfully!\n";
