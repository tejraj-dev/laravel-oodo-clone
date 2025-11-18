<?php

namespace App\Modules\Accounting\Filament\Resources\JournalEntryResource\Pages;

use App\Modules\Accounting\Filament\Resources\JournalEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJournalEntry extends CreateRecord
{
    protected static string $resource = JournalEntryResource::class;
}
