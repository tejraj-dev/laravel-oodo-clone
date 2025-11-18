<?php

namespace App\Modules\Sales\Filament\Resources\SalesOrderResource\Pages;

use App\Modules\Sales\Filament\Resources\SalesOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesOrder extends CreateRecord
{
    protected static string $resource = SalesOrderResource::class;
}
