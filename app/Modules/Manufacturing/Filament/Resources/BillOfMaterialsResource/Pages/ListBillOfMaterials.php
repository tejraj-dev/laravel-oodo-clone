<?php

namespace App\Modules\Manufacturing\Filament\Resources\BillOfMaterialsResource\Pages;

use App\Modules\Manufacturing\Filament\Resources\BillOfMaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBillOfMaterials extends ListRecords
{
    protected static string $resource = BillOfMaterialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
