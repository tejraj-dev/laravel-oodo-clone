<?php

namespace App\Modules\Email\Filament\Resources\EmailRecipientResource\Pages;

use App\Modules\Email\Filament\Resources\EmailRecipientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailRecipient extends EditRecord
{
    protected static string $resource = EmailRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
