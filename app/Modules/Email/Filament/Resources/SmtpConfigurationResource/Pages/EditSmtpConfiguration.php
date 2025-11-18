<?php

namespace App\Modules\Email\Filament\Resources\SmtpConfigurationResource\Pages;

use App\Modules\Email\Filament\Resources\SmtpConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmtpConfiguration extends EditRecord
{
    protected static string $resource = SmtpConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
