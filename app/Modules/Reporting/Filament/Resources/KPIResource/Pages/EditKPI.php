<?php

namespace App\Modules\Reporting\Filament\Resources\KPIResource\Pages;

use App\Modules\Reporting\Filament\Resources\KPIResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKPI extends EditRecord
{
    protected static string $resource = KPIResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
