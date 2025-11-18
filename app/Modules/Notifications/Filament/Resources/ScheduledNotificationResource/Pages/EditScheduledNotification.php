<?php

namespace App\Modules\Notifications\Filament\Resources\ScheduledNotificationResource\Pages;

use App\Modules\Notifications\Filament\Resources\ScheduledNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScheduledNotification extends EditRecord
{
    protected static string $resource = ScheduledNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
