<?php

namespace App\Modules\Projects\Filament\Resources\TaskResource\Pages;

use App\Modules\Projects\Filament\Resources\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
}
