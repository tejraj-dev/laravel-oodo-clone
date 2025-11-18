<?php

namespace App\Modules\Manufacturing\Filament\Resources;

use App\Modules\Manufacturing\Filament\Resources\BillOfMaterialsResource\Pages;
use App\Modules\Manufacturing\Models\BillOfMaterials;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BillOfMaterialsResource extends Resource
{
    protected static ?string $model = BillOfMaterials::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Manufacturing';

    protected static ?int $navigationSort = 1;

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
            'index' => Pages\ListBillOfMaterials::route('/'),
            'create' => Pages\CreateBillOfMaterials::route('/create'),
            'edit' => Pages\EditBillOfMaterials::route('/{record}/edit'),
        ];
    }
}
