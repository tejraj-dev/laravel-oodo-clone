<?php

namespace App\Modules\Documents\Filament\Resources;

use App\Modules\Documents\Models\DocumentVersion;
use App\Modules\Documents\Filament\Resources\DocumentVersionResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentVersionResource extends Resource
{
    protected static ?string $model = DocumentVersion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Documents';

    protected static ?string $navigationLabel = 'Document Versions';

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
            'index' => Pages\ListDocumentVersions::route('/'),
            'create' => Pages\CreateDocumentVersion::route('/create'),
            'edit' => Pages\EditDocumentVersion::route('/{record}/edit'),
        ];
    }
}
