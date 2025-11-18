<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalStepResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalStepResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalStep extends CreateRecord
{
    protected static string $resource = ApprovalStepResource::class;
}
