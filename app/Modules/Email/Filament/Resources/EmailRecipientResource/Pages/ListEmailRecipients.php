<?php

namespace App\Modules\Email\Filament\Resources\EmailRecipientResource\Pages;

use App\Modules\Email\Filament\Resources\EmailRecipientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailRecipients extends ListRecords
{
    protected static string $resource = EmailRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
