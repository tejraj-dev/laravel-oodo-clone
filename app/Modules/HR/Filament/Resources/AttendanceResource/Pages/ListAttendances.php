<?php

namespace App\Modules\HR\Filament\Resources\AttendanceResource\Pages;

use App\Modules\HR\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
