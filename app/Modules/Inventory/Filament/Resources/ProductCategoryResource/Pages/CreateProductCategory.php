<?php

namespace App\Modules\Inventory\Filament\Resources\ProductCategoryResource\Pages;

use App\Modules\Inventory\Filament\Resources\ProductCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
