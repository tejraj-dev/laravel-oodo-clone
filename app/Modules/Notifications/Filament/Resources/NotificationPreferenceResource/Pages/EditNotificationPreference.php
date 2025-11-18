<?php

namespace App\Modules\Notifications\Filament\Resources\NotificationPreferenceResource\Pages;

use App\Modules\Notifications\Filament\Resources\NotificationPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificationPreference extends EditRecord
{
    protected static string $resource = NotificationPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
