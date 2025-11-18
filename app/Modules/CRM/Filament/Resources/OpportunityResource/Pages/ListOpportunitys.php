<?php

namespace App\Modules\CRM\Filament\Resources\OpportunityResource\Pages;

use App\Modules\CRM\Filament\Resources\OpportunityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpportunitys extends ListRecords
{
    protected static string $resource = OpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
