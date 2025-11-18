<?php

namespace App\Modules\Accounting\Filament\Resources\JournalEntryResource\Pages;

use App\Modules\Accounting\Filament\Resources\JournalEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJournalEntrys extends ListRecords
{
    protected static string $resource = JournalEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
