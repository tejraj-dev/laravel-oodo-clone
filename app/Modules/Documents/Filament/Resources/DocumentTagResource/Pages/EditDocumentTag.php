<?php

namespace App\Modules\Documents\Filament\Resources\DocumentTagResource\Pages;

use App\Modules\Documents\Filament\Resources\DocumentTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentTag extends EditRecord
{
    protected static string $resource = DocumentTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
