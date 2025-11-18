<?php

namespace App\Modules\Notifications\Filament\Resources;

use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Filament\Resources\NotificationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Notifications';

    protected static ?string $navigationLabel = 'Notifications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Notification Details')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('notification_template_id')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'order' => 'Order',
                                'payment' => 'Payment',
                                'inventory' => 'Inventory',
                                'crm' => 'CRM',
                                'hr' => 'HR',
                                'system' => 'System',
                                'custom' => 'Custom',
                            ]),
                        Forms\Components\Select::make('channel')
                            ->required()
                            ->options([
                                'database' => 'Database',
                                'email' => 'Email',
                                'sms' => 'SMS',
                                'push' => 'Push Notification',
                                'slack' => 'Slack',
                            ]),
                        Forms\Components\Select::make('priority')
                            ->required()
                            ->default('normal')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('message')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('action_url')
                            ->maxLength(255)
                            ->url(),
                        Forms\Components\TextInput::make('action_text')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('Mark as Read'),
                        Forms\Components\DateTimePicker::make('read_at')
                            ->label('Read At'),
                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label('Sent At'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'order',
                        'success' => 'payment',
                        'warning' => 'inventory',
                        'info' => 'crm',
                        'danger' => 'system',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'primary' => 'normal',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'order' => 'Order',
                        'payment' => 'Payment',
                        'inventory' => 'Inventory',
                        'crm' => 'CRM',
                        'hr' => 'HR',
                        'system' => 'System',
                        'custom' => 'Custom',
                    ]),
                Tables\Filters\SelectFilter::make('channel')
                    ->options([
                        'database' => 'Database',
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'push' => 'Push',
                        'slack' => 'Slack',
                    ]),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
                Tables\Filters\Filter::make('priority')
                    ->form([
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'normal' => 'Normal',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ]),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['priority'], fn($q) => $q->where('priority', $data['priority']));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsRead')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->action(fn (Notification $record) => $record->markAsRead())
                    ->visible(fn (Notification $record) => !$record->is_read),
                Tables\Actions\Action::make('markAsUnread')
                    ->label('Mark as Unread')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (Notification $record) => $record->markAsUnread())
                    ->visible(fn (Notification $record) => $record->is_read),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsRead')
                        ->label('Mark as Read')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->markAsRead()),
                    Tables\Actions\BulkAction::make('markAsUnread')
                        ->label('Mark as Unread')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->markAsUnread()),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
