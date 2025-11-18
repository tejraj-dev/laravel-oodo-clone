<?php

namespace App\Modules\Documents\Filament\Resources\DocumentPermissionResource\Pages;

use App\Modules\Documents\Filament\Resources\DocumentPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentPermission extends EditRecord
{
    protected static string $resource = DocumentPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
