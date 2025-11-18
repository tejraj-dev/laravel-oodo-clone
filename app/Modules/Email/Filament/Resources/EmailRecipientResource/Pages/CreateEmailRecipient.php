<?php

namespace App\Modules\Email\Filament\Resources\EmailRecipientResource\Pages;

use App\Modules\Email\Filament\Resources\EmailRecipientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailRecipient extends CreateRecord
{
    protected static string $resource = EmailRecipientResource::class;
}
