<?php

namespace App\Modules\Reporting\Filament\Resources\ReportResource\Pages;

use App\Modules\Reporting\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
