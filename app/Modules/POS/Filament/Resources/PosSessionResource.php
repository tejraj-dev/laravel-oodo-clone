<?php

namespace App\Modules\POS\Filament\Resources;

use App\Modules\POS\Filament\Resources\PosSessionResource\Pages;
use App\Modules\POS\Models\PosSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PosSessionResource extends Resource
{
    protected static ?string $model = PosSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'POS';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Session Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('session_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('cash_register_id')
                            ->relationship('cashRegister', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'open' => 'Open',
                                'closed' => 'Closed',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Session Times')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_time')
                            ->required(),
                        Forms\Components\DateTimePicker::make('end_time'),
                    ])->columns(2),

                Forms\Components\Section::make('Cash Management')
                    ->schema([
                        Forms\Components\TextInput::make('opening_balance')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('closing_balance')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('expected_closing_balance')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('cash_in')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('cash_out')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('Sales Summary')
                    ->schema([
                        Forms\Components\TextInput::make('total_sales')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                        Forms\Components\TextInput::make('total_refunds')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                    ])->columns(2),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cashRegister.name')
                    ->label('Cash Register')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cashier')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_sales')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'open',
                        'danger' => 'closed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('cash_register_id')
                    ->relationship('cashRegister', 'name')
                    ->label('Cash Register'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_time', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosSessions::route('/'),
            'create' => Pages\CreatePosSession::route('/create'),
            'edit' => Pages\EditPosSession::route('/{record}/edit'),
        ];
    }
}
