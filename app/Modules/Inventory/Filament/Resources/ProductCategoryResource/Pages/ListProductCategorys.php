<?php

namespace App\Modules\Inventory\Filament\Resources\ProductCategoryResource\Pages;

use App\Modules\Inventory\Filament\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductCategorys extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
