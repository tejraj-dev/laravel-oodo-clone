<?php

namespace App\Modules\Sales\Filament\Resources\SalesOrderResource\Pages;

use App\Modules\Sales\Filament\Resources\SalesOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesOrders extends ListRecords
{
    protected static string $resource = SalesOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
