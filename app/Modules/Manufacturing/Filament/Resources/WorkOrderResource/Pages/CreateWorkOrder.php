<?php

namespace App\Modules\Manufacturing\Filament\Resources\WorkOrderResource\Pages;

use App\Modules\Manufacturing\Filament\Resources\WorkOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkOrder extends CreateRecord
{
    protected static string $resource = WorkOrderResource::class;
}
