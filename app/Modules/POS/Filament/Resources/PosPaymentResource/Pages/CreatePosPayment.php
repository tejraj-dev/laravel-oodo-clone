<?php

namespace App\Modules\POS\Filament\Resources\PosPaymentResource\Pages;

use App\Modules\POS\Filament\Resources\PosPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosPayment extends CreateRecord
{
    protected static string $resource = PosPaymentResource::class;
}
