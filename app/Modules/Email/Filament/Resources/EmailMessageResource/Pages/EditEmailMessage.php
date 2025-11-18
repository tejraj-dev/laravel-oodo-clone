<?php

namespace App\Modules\Email\Filament\Resources\EmailMessageResource\Pages;

use App\Modules\Email\Filament\Resources\EmailMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailMessage extends EditRecord
{
    protected static string $resource = EmailMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
