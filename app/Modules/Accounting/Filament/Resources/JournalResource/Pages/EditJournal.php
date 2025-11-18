<?php

namespace App\Modules\Accounting\Filament\Resources\JournalResource\Pages;

use App\Modules\Accounting\Filament\Resources\JournalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJournal extends EditRecord
{
    protected static string $resource = JournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
