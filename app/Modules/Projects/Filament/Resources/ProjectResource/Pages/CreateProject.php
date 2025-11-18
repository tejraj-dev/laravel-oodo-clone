<?php

namespace App\Modules\Projects\Filament\Resources\ProjectResource\Pages;

use App\Modules\Projects\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
}
