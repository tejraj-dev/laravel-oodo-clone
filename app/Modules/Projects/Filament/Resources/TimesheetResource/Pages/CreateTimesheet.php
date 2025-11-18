<?php

namespace App\Modules\Projects\Filament\Resources\TimesheetResource\Pages;

use App\Modules\Projects\Filament\Resources\TimesheetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimesheet extends CreateRecord
{
    protected static string $resource = TimesheetResource::class;
}
