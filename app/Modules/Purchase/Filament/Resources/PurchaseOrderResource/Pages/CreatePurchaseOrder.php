<?php

namespace App\Modules\Purchase\Filament\Resources\PurchaseOrderResource\Pages;

use App\Modules\Purchase\Filament\Resources\PurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;
}
