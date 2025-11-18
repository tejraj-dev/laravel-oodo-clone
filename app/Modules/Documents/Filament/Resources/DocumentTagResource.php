<?php

namespace App\Modules\Documents\Filament\Resources;

use App\Modules\Documents\Models\DocumentTag;
use App\Modules\Documents\Filament\Resources\DocumentTagResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentTagResource extends Resource
{
    protected static ?string $model = DocumentTag::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Documents';

    protected static ?string $navigationLabel = 'Document Tags';

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
            'index' => Pages\ListDocumentTags::route('/'),
            'create' => Pages\CreateDocumentTag::route('/create'),
            'edit' => Pages\EditDocumentTag::route('/{record}/edit'),
        ];
    }
}
