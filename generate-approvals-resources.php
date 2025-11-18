<?php

$resources = [
    'ApprovalWorkflow' => 'Approval Workflows',
    'ApprovalStep' => 'Approval Steps',
    'ApprovalRequest' => 'Approval Requests',
    'ApprovalRule' => 'Approval Rules',
    'ApprovalDelegate' => 'Approval Delegates',
];

foreach ($resources as $resource => $label) {
    $resourceClass = "{$resource}Resource";
    $modelClass = "App\\Modules\\Approvals\\Models\\{$resource}";

    $resourceContent = <<<PHP
<?php

namespace App\Modules\Approvals\Filament\Resources;

use {$modelClass};
use App\Modules\Approvals\Filament\Resources\\{$resourceClass}\\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class {$resourceClass} extends Resource
{
    protected static ?string \$model = {$resource}::class;

    protected static ?string \$navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string \$navigationGroup = 'Approvals';

    protected static ?string \$navigationLabel = '{$label}';

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                // Form fields will be added here
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                // Table columns will be added here
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\List{$resource}s::route('/'),
            'create' => Pages\Create{$resource}::route('/create'),
            'edit' => Pages\Edit{$resource}::route('/{record}/edit'),
        ];
    }
}

PHP;

    file_put_contents("app/Modules/Approvals/Filament/Resources/{$resourceClass}.php", $resourceContent);
    mkdir("app/Modules/Approvals/Filament/Resources/{$resourceClass}/Pages", 0755, true);

    $listContent = <<<PHP
<?php

namespace App\Modules\Approvals\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Approvals\Filament\Resources\\{$resourceClass};
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class List{$resource}s extends ListRecords
{
    protected static string \$resource = {$resourceClass}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

PHP;
    file_put_contents("app/Modules/Approvals/Filament/Resources/{$resourceClass}/Pages/List{$resource}s.php", $listContent);

    $createContent = <<<PHP
<?php

namespace App\Modules\Approvals\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Approvals\Filament\Resources\\{$resourceClass};
use Filament\Resources\Pages\CreateRecord;

class Create{$resource} extends CreateRecord
{
    protected static string \$resource = {$resourceClass}::class;
}

PHP;
    file_put_contents("app/Modules/Approvals/Filament/Resources/{$resourceClass}/Pages/Create{$resource}.php", $createContent);

    $editContent = <<<PHP
<?php

namespace App\Modules\Approvals\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Approvals\Filament\Resources\\{$resourceClass};
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit{$resource} extends EditRecord
{
    protected static string \$resource = {$resourceClass}::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

PHP;
    file_put_contents("app/Modules/Approvals/Filament/Resources/{$resourceClass}/Pages/Edit{$resource}.php", $editContent);

    echo "Created {$resourceClass}\n";
}

echo "All Approvals resources created successfully!\n";
