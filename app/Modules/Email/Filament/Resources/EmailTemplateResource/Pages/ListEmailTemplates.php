<?php

namespace App\Modules\Email\Filament\Resources\EmailTemplateResource\Pages;

use App\Modules\Email\Filament\Resources\EmailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailTemplates extends ListRecords
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
