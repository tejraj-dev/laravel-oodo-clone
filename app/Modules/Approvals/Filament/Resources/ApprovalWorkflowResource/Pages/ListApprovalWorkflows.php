<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalWorkflowResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalWorkflowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalWorkflows extends ListRecords
{
    protected static string $resource = ApprovalWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
