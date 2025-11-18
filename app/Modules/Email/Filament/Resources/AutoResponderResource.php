<?php

namespace App\Modules\Email\Filament\Resources;

use App\Modules\Email\Models\AutoResponder;
use App\Modules\Email\Filament\Resources\AutoResponderResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AutoResponderResource extends Resource
{
    protected static ?string $model = AutoResponder::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Email';

    protected static ?string $navigationLabel = 'Auto Responders';

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
            'index' => Pages\ListAutoResponders::route('/'),
            'create' => Pages\CreateAutoResponder::route('/create'),
            'edit' => Pages\EditAutoResponder::route('/{record}/edit'),
        ];
    }
}
