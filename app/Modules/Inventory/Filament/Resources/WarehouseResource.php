<?php

namespace App\Modules\Inventory\Filament\Resources;

use App\Modules\Inventory\Filament\Resources\WarehouseResource\Pages;
use App\Modules\Inventory\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Add form fields here
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
