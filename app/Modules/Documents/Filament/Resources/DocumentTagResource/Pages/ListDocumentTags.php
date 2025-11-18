<?php

namespace App\Modules\Documents\Filament\Resources\DocumentTagResource\Pages;

use App\Modules\Documents\Filament\Resources\DocumentTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentTags extends ListRecords
{
    protected static string $resource = DocumentTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
