<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalDelegateResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalDelegateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalDelegates extends ListRecords
{
    protected static string $resource = ApprovalDelegateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
