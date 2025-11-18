<?php

/**
 * Generate Filament Resources for Reporting Module
 */

$resources = [
    'Dashboard' => ['icon' => 'heroicon-o-squares-2x2', 'sort' => 2],
    'KPI' => ['icon' => 'heroicon-o-chart-bar-square', 'sort' => 3],
    'ReportTemplate' => ['icon' => 'heroicon-o-document-duplicate', 'sort' => 4],
    'ScheduledReport' => ['icon' => 'heroicon-o-clock', 'sort' => 5],
    'Widget' => ['icon' => 'heroicon-o-square-3-stack-3d', 'sort' => 6],
];

function createPages($model, $plural) {
    $pagesDir = __DIR__ . "/app/Modules/Reporting/Filament/Resources/{$model}Resource/Pages";
    if (!is_dir($pagesDir)) {
        mkdir($pagesDir, 0755, true);
    }

    // List Page
    $listContent = "<?php

namespace App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource;
use Filament\\Actions;
use Filament\\Resources\\Pages\\ListRecords;

class List{$plural} extends ListRecords
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
    file_put_contents("{$pagesDir}/List{$plural}.php", $listContent);

    // Create Page
    $createContent = "<?php

namespace App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$model} extends CreateRecord
{
    protected static string \$resource = {$model}Resource::class;
}
";
    file_put_contents("{$pagesDir}/Create{$model}.php", $createContent);

    // Edit Page
    $editContent = "<?php

namespace App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource;
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

// Generate pages for Report (already created resource)
createPages('Report', 'Reports');

// Generate resources and pages for other models
foreach ($resources as $model => $config) {
    $resourcesDir = __DIR__ . "/app/Modules/Reporting/Filament/Resources";

    $resourceContent = "<?php

namespace App\\Modules\\Reporting\\Filament\\Resources;

use App\\Modules\\Reporting\\Filament\\Resources\\{$model}Resource\\Pages;
use App\\Modules\\Reporting\\Models\\{$model};
use Filament\\Forms;
use Filament\\Forms\\Form;
use Filament\\Resources\\Resource;
use Filament\\Tables;
use Filament\\Tables\\Table;

class {$model}Resource extends Resource
{
    protected static ?string \$model = {$model}::class;

    protected static ?string \$navigationIcon = '{$config['icon']}';

    protected static ?string \$navigationGroup = 'Reporting';

    protected static ?int \$navigationSort = {$config['sort']};

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                Forms\\Components\\Section::make('Information')
                    ->schema([
                        Forms\\Components\\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\\Components\\Textarea::make('description')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                Tables\\Columns\\TextColumn::make('name')
                    ->searchable()
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
            'index' => Pages\\List" . str_replace('KPI', 'Kpis', $model) . "s::route('/'),
            'create' => Pages\\Create{$model}::route('/create'),
            'edit' => Pages\\Edit{$model}::route('/{record}/edit'),
        ];
    }
}
";

    file_put_contents("{$resourcesDir}/{$model}Resource.php", $resourceContent);
    echo "Created {$model}Resource.php\n";

    // Create pages
    $plural = $model === 'KPI' ? 'Kpis' : $model . 's';
    createPages($model, $plural);
}

echo "\nAll Reporting module resources generated successfully!\n";
