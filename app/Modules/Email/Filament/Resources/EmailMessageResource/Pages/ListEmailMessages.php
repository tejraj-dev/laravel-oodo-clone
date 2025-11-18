<?php

namespace App\Modules\Email\Filament\Resources\EmailMessageResource\Pages;

use App\Modules\Email\Filament\Resources\EmailMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailMessages extends ListRecords
{
    protected static string $resource = EmailMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
