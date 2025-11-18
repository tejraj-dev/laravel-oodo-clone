<?php

namespace App\Modules\ImportExport\Filament\Resources\ImportJobResource\Pages;

use App\Modules\ImportExport\Filament\Resources\ImportJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImportJobs extends ListRecords
{
    protected static string $resource = ImportJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
