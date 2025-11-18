<?php

namespace App\Modules\POS\Filament\Resources\PaymentMethodResource\Pages;

use App\Modules\POS\Filament\Resources\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
