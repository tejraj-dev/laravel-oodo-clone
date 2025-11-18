<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalRequestResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalRequests extends ListRecords
{
    protected static string $resource = ApprovalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
