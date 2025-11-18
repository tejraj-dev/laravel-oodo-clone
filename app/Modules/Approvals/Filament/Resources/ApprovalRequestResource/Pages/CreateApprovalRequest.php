<?php

namespace App\Modules\Approvals\Filament\Resources\ApprovalRequestResource\Pages;

use App\Modules\Approvals\Filament\Resources\ApprovalRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApprovalRequest extends CreateRecord
{
    protected static string $resource = ApprovalRequestResource::class;
}
