<?php

/**
 * Filament Resource Generator for POS Module
 */

$resources = [
    'PaymentMethod' => ['icon' => 'heroicon-o-credit-card', 'sort' => 2],
    'PosSession' => ['icon' => 'heroicon-o-clock', 'sort' => 3],
    'PosOrder' => ['icon' => 'heroicon-o-shopping-cart', 'sort' => 4],
    'PosPayment' => ['icon' => 'heroicon-o-banknotes', 'sort' => 5],
];

function createPages($model, $modelPlural) {
    $pagesDir = __DIR__ . "/app/Modules/POS/Filament/Resources/{$model}Resource/Pages";
    if (!is_dir($pagesDir)) {
        mkdir($pagesDir, 0755, true);
    }

    // List Page
    $listContent = "<?php

namespace App\\Modules\\POS\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\POS\\Filament\\Resources\\{$model}Resource;
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

namespace App\\Modules\\POS\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\POS\\Filament\\Resources\\{$model}Resource;
use Filament\\Resources\\Pages\\CreateRecord;

class Create{$model} extends CreateRecord
{
    protected static string \$resource = {$model}Resource::class;
}
";
    file_put_contents("{$pagesDir}/Create{$model}.php", $createContent);

    // Edit Page
    $editContent = "<?php

namespace App\\Modules\\POS\\Filament\\Resources\\{$model}Resource\\Pages;

use App\\Modules\\POS\\Filament\\Resources\\{$model}Resource;
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

// Generate pages for all POS resources
$pageResources = [
    'PosSession' => 'PosSessions',
    'PosOrder' => 'PosOrders',
    'PosPayment' => 'PosPayments',
];

foreach ($pageResources as $model => $plural) {
    createPages($model, $plural);
}

echo "\nAll POS resource pages generated successfully!\n";
