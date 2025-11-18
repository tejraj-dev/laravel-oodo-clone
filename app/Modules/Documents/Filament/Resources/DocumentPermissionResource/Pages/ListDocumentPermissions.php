<?php

namespace App\Modules\Documents\Filament\Resources\DocumentPermissionResource\Pages;

use App\Modules\Documents\Filament\Resources\DocumentPermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentPermissions extends ListRecords
{
    protected static string $resource = DocumentPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
