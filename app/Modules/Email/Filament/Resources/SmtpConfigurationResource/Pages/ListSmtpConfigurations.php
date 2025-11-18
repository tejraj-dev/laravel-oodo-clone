<?php

namespace App\Modules\Email\Filament\Resources\SmtpConfigurationResource\Pages;

use App\Modules\Email\Filament\Resources\SmtpConfigurationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmtpConfigurations extends ListRecords
{
    protected static string $resource = SmtpConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
