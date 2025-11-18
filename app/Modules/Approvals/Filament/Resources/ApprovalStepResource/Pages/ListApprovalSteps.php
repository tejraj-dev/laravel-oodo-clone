<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalStepResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalStepResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalSteps extends ListRecords
{
    protected static string $resource = ApprovalStepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
