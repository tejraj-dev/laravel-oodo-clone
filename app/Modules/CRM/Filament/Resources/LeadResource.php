<?php

namespace App\Modules\CRM\Filament\Resources;

use App\Modules\CRM\Filament\Resources\LeadResource\Pages;
use App\Modules\CRM\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Select::make('title')
                            ->options([
                                'Mr' => 'Mr',
                                'Mrs' => 'Mrs',
                                'Ms' => 'Ms',
                                'Dr' => 'Dr',
                            ]),
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('mobile')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('job_title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('industry')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Lead Details')
                    ->schema([
                        Forms\Components\Select::make('lead_source')
                            ->options([
                                'website' => 'Website',
                                'referral' => 'Referral',
                                'cold_call' => 'Cold Call',
                                'social_media' => 'Social Media',
                                'email_campaign' => 'Email Campaign',
                                'trade_show' => 'Trade Show',
                            ]),
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'contacted' => 'Contacted',
                                'qualified' => 'Qualified',
                                'converted' => 'Converted',
                                'lost' => 'Lost',
                            ])
                            ->default('new')
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                            ])
                            ->default('medium'),
                        Forms\Components\Select::make('rating')
                            ->options([
                                'hot' => 'Hot',
                                'warm' => 'Warm',
                                'cold' => 'Cold',
                            ]),
                        Forms\Components\TextInput::make('expected_revenue')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('probability')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignedUser', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('postal_code')
                            ->maxLength(255),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'new',
                        'info' => 'contacted',
                        'warning' => 'qualified',
                        'success' => 'converted',
                        'danger' => 'lost',
                    ]),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'success' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                    ]),
                Tables\Columns\TextColumn::make('expected_revenue')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('priority'),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedUser', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
