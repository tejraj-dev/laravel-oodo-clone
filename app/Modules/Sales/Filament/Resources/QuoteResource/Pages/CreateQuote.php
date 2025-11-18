<?php

namespace App\Modules\Sales\Filament\Resources\QuoteResource\Pages;

use App\Modules\Sales\Filament\Resources\QuoteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;
}
