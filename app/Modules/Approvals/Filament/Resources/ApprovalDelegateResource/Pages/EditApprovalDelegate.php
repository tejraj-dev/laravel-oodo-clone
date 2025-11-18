<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalDelegateResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalDelegateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalDelegate extends EditRecord
{
    protected static string $resource = ApprovalDelegateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
