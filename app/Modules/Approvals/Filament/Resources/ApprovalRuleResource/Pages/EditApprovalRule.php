<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalRuleResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalRule extends EditRecord
{
    protected static string $resource = ApprovalRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
