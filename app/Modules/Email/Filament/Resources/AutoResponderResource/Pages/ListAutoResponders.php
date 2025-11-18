<?php

namespace App\Modules\Email\Filament\Resources\AutoResponderResource\Pages;

use App\Modules\Email\Filament\Resources\AutoResponderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutoResponders extends ListRecords
{
    protected static string $resource = AutoResponderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
