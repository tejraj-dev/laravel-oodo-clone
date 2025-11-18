<?php

$resources = [
    'NotificationTemplate' => 'Notification Templates',
    'Notification' => 'Notifications',
    'NotificationPreference' => 'Notification Preferences',
    'ScheduledNotification' => 'Scheduled Notifications',
];

foreach ($resources as $resource => $label) {
    $resourceClass = "{$resource}Resource";
    $modelClass = "App\\Modules\\Notifications\\Models\\{$resource}";

    // Create Resource file
    $resourceContent = <<<PHP
<?php

namespace App\Modules\Notifications\Filament\Resources;

use {$modelClass};
use App\Modules\Notifications\Filament\Resources\\{$resourceClass}\\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class {$resourceClass} extends Resource
{
    protected static ?string \$model = {$resource}::class;

    protected static ?string \$navigationIcon = 'heroicon-o-bell';

    protected static ?string \$navigationGroup = 'Notifications';

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

    file_put_contents("app/Modules/Notifications/Filament/Resources/{$resourceClass}.php", $resourceContent);

    // Create Pages directory
    mkdir("app/Modules/Notifications/Filament/Resources/{$resourceClass}", 0755, true);
    mkdir("app/Modules/Notifications/Filament/Resources/{$resourceClass}/Pages", 0755, true);

    // Create List page
    $listContent = <<<PHP
<?php

namespace App\Modules\Notifications\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Notifications\Filament\Resources\\{$resourceClass};
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

    file_put_contents("app/Modules/Notifications/Filament/Resources/{$resourceClass}/Pages/List{$resource}s.php", $listContent);

    // Create Create page
    $createContent = <<<PHP
<?php

namespace App\Modules\Notifications\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Notifications\Filament\Resources\\{$resourceClass};
use Filament\Resources\Pages\CreateRecord;

class Create{$resource} extends CreateRecord
{
    protected static string \$resource = {$resourceClass}::class;
}

PHP;

    file_put_contents("app/Modules/Notifications/Filament/Resources/{$resourceClass}/Pages/Create{$resource}.php", $createContent);

    // Create Edit page
    $editContent = <<<PHP
<?php

namespace App\Modules\Notifications\Filament\Resources\\{$resourceClass}\\Pages;

use App\Modules\Notifications\Filament\Resources\\{$resourceClass};
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

    file_put_contents("app/Modules/Notifications/Filament/Resources/{$resourceClass}/Pages/Edit{$resource}.php", $editContent);

    echo "Created {$resourceClass}\n";
}

echo "All Notification resources created successfully!\n";
