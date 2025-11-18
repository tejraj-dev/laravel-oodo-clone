<?php

namespace App\Modules\Email\Filament\Resources\EmailTemplateResource\Pages;

use App\Modules\Email\Filament\Resources\EmailTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailTemplate extends CreateRecord
{
    protected static string $resource = EmailTemplateResource::class;
}
