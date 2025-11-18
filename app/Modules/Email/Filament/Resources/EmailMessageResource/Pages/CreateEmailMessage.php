<?php

namespace App\Modules\Email\Filament\Resources\EmailMessageResource\Pages;

use App\Modules\Email\Filament\Resources\EmailMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailMessage extends CreateRecord
{
    protected static string $resource = EmailMessageResource::class;
}
