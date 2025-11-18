<?php

namespace App\Modules\POS\Filament\Resources\PosPaymentResource\Pages;

use App\Modules\POS\Filament\Resources\PosPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPosPayments extends ListRecords
{
    protected static string $resource = PosPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
