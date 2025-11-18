<?php

namespace App\Modules\Reporting\Filament\Resources\ReportTemplateResource\Pages;

use App\Modules\Reporting\Filament\Resources\ReportTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportTemplate extends EditRecord
{
    protected static string $resource = ReportTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
