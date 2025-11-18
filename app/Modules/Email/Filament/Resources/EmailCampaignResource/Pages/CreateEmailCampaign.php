<?php

namespace App\Modules\Email\Filament\Resources\EmailCampaignResource\Pages;

use App\Modules\Email\Filament\Resources\EmailCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailCampaign extends CreateRecord
{
    protected static string $resource = EmailCampaignResource::class;
}
