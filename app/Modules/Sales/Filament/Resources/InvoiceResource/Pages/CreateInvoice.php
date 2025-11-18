<?php

namespace App\Modules\Sales\Filament\Resources\InvoiceResource\Pages;

use App\Modules\Sales\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
