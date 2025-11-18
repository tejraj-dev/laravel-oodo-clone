<?php

namespace App\Modules\POS\Filament\Resources\PosSessionResource\Pages;

use App\Modules\POS\Filament\Resources\PosSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosSession extends EditRecord
{
    protected static string $resource = PosSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
