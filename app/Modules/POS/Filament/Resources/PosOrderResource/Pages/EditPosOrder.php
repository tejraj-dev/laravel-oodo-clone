<?php

namespace App\Modules\POS\Filament\Resources\PosOrderResource\Pages;

use App\Modules\POS\Filament\Resources\PosOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosOrder extends EditRecord
{
    protected static string $resource = PosOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
