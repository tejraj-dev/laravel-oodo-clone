<?php

namespace App\Modules\ImportExport\Filament\Resources\ExportJobResource\Pages;

use App\Modules\ImportExport\Filament\Resources\ExportJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExportJob extends EditRecord
{
    protected static string $resource = ExportJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
