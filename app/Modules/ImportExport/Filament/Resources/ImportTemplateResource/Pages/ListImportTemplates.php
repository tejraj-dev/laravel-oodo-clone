<?php

namespace App\Modules\ImportExport\Filament\Resources\ImportTemplateResource\Pages;

use App\Modules\ImportExport\Filament\Resources\ImportTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImportTemplates extends ListRecords
{
    protected static string $resource = ImportTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
