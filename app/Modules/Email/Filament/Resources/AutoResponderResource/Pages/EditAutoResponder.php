<?php

namespace App\Modules\Email\Filament\Resources\AutoResponderResource\Pages;

use App\Modules\Email\Filament\Resources\AutoResponderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAutoResponder extends EditRecord
{
    protected static string $resource = AutoResponderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
