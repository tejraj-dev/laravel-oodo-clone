<?php

namespace App\Modules\HR\Filament\Resources\EmployeeResource\Pages;

use App\Modules\HR\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
