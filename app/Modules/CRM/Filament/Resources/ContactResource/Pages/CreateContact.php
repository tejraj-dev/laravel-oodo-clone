<?php

namespace App\Modules\CRM\Filament\Resources\ContactResource\Pages;

use App\Modules\CRM\Filament\Resources\ContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}
