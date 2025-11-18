<?php

namespace App\Modules\HR\Filament\Resources\AttendanceResource\Pages;

use App\Modules\HR\Filament\Resources\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
