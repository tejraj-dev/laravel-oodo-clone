<?php

namespace App\Modules\Manufacturing\Filament\Resources\BillOfMaterialsResource\Pages;

use App\Modules\Manufacturing\Filament\Resources\BillOfMaterialsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBillOfMaterials extends EditRecord
{
    protected static string $resource = BillOfMaterialsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
