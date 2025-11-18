<?php

namespace App\Modules\Email\Filament\Resources\SmtpConfigurationResource\Pages;

use App\Modules\Email\Filament\Resources\SmtpConfigurationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSmtpConfiguration extends CreateRecord
{
    protected static string $resource = SmtpConfigurationResource::class;
}
