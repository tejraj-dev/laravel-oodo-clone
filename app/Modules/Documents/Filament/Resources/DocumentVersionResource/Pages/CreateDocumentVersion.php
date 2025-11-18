<?php

namespace App\Modules\Documents\Filament\Resources\DocumentVersionResource\Pages;

use App\Modules\Documents\Filament\Resources\DocumentVersionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentVersion extends CreateRecord
{
    protected static string $resource = DocumentVersionResource::class;
}
