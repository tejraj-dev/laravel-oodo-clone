<?php

namespace App\Modules\POS\Filament\Resources\PosOrderResource\Pages;

use App\Modules\POS\Filament\Resources\PosOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePosOrder extends CreateRecord
{
    protected static string $resource = PosOrderResource::class;
}
