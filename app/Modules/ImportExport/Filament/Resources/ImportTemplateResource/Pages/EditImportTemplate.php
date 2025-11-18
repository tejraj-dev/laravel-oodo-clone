<?php

namespace App\Modules\ImportExport\Filament\Resources\ImportTemplateResource\Pages;

use App\Modules\ImportExport\Filament\Resources\ImportTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportTemplate extends EditRecord
{
    protected static string $resource = ImportTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
