<?php

namespace App\Modules\POS\Filament\Resources;

use App\Modules\POS\Filament\Resources\CashRegisterResource\Pages;
use App\Modules\POS\Models\CashRegister;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'POS';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('location')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(3),

                Forms\Components\Section::make('Configuration')
                    ->schema([
                        Forms\Components\Toggle::make('auto_close_session')
                            ->label('Auto Close Session'),
                        Forms\Components\Toggle::make('allow_discount')
                            ->label('Allow Discounts')
                            ->reactive(),
                        Forms\Components\TextInput::make('max_discount_percent')
                            ->label('Max Discount %')
                            ->numeric()
                            ->suffix('%')
                            ->visible(fn (callable $get) => $get('allow_discount')),
                        Forms\Components\Toggle::make('require_customer')
                            ->label('Require Customer'),
                        Forms\Components\Toggle::make('allow_credit_sale')
                            ->label('Allow Credit Sales'),
                    ])->columns(2),

                Forms\Components\Section::make('Receipt Settings')
                    ->schema([
                        Forms\Components\Textarea::make('receipt_header')
                            ->rows(3),
                        Forms\Components\Textarea::make('receipt_footer')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Printer Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('printer_ip')
                            ->label('Printer IP Address'),
                        Forms\Components\TextInput::make('printer_port')
                            ->label('Printer Port')
                            ->numeric(),
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
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\IconColumn::make('allow_discount')
                    ->boolean()
                    ->label('Discounts'),
                Tables\Columns\TextColumn::make('max_discount_percent')
                    ->label('Max Discount')
                    ->suffix('%')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCashRegisters::route('/'),
            'create' => Pages\CreateCashRegister::route('/create'),
            'edit' => Pages\EditCashRegister::route('/{record}/edit'),
        ];
    }
}
