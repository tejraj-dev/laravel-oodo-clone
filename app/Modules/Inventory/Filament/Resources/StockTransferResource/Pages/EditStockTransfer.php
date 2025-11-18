<?php

namespace App\Modules\Inventory\Filament\Resources\StockTransferResource\Pages;

use App\Modules\Inventory\Filament\Resources\StockTransferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockTransfer extends EditRecord
{
    protected static string $resource = StockTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
