<?php

namespace App\Modules\HR\Filament\Resources\LeaveRequestResource\Pages;

use App\Modules\HR\Filament\Resources\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
}
