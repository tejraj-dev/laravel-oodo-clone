<?php

namespace App\Modules\Notifications\Filament\Resources;

use App\Modules\Notifications\Models\ScheduledNotification;
use App\Modules\Notifications\Filament\Resources\ScheduledNotificationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScheduledNotificationResource extends Resource
{
    protected static ?string $model = ScheduledNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Notifications';

    protected static ?string $navigationLabel = 'Scheduled Notifications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form fields will be added here
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Table columns will be added here
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduledNotifications::route('/'),
            'create' => Pages\CreateScheduledNotification::route('/create'),
            'edit' => Pages\EditScheduledNotification::route('/{record}/edit'),
        ];
    }
}
