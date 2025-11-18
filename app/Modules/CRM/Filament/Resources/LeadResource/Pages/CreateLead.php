<?php

namespace App\Modules\CRM\Filament\Resources\LeadResource\Pages;

use App\Modules\CRM\Filament\Resources\LeadResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;
}
