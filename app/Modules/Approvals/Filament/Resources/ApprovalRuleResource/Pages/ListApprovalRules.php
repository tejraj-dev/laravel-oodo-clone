<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalRuleResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalRules extends ListRecords
{
    protected static string $resource = ApprovalRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
