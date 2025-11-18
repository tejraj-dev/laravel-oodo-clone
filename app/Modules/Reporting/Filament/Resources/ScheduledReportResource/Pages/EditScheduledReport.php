<?php

namespace App\Modules\Reporting\Filament\Resources\ScheduledReportResource\Pages;

use App\Modules\Reporting\Filament\Resources\ScheduledReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScheduledReport extends EditRecord
{
    protected static string $resource = ScheduledReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
