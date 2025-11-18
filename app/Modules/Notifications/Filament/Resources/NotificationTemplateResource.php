<?php

namespace App\Modules\Notifications\Filament\Resources;

use App\Modules\Notifications\Models\NotificationTemplate;
use App\Modules\Notifications\Filament\Resources\NotificationTemplateResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Notifications';

    protected static ?string $navigationLabel = 'Notification Templates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Unique identifier for the template'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Notification Settings')
                    ->schema([
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
                        Forms\Components\Select::make('channels')
                            ->multiple()
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
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Templates')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->maxLength(255)
                            ->helperText('Email subject line'),
                        Forms\Components\Textarea::make('message_template')
                            ->required()
                            ->rows(4)
                            ->helperText('Use {{variable_name}} for dynamic content')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('email_template')
                            ->rows(6)
                            ->helperText('HTML email template (optional)')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('sms_template')
                            ->rows(3)
                            ->helperText('SMS template - keep it short')
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('variables')
                            ->helperText('Available variables for this template')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Action')
                    ->schema([
                        Forms\Components\TextInput::make('action_url_template')
                            ->maxLength(255)
                            ->helperText('URL template for action button (use {{variables}})'),
                        Forms\Components\TextInput::make('action_text')
                            ->maxLength(255)
                            ->helperText('Text for action button'),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_system_template')
                            ->label('System Template')
                            ->helperText('System templates cannot be deleted'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('channels')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'primary' => 'normal',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ]),
                Tables\Columns\IconColumn::make('is_system_template')
                    ->label('System')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\TernaryFilter::make('is_system_template')
                    ->label('System Template'),
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
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
