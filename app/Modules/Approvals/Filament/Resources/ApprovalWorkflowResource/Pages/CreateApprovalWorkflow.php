<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalWorkflowResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalWorkflowResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalWorkflow extends CreateRecord
{
    protected static string $resource = ApprovalWorkflowResource::class;
}
