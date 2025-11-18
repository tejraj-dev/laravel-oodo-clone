<?php

namespace App\Modules\HR\Filament\Resources\LeaveRequestResource\Pages;

use App\Modules\HR\Filament\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
