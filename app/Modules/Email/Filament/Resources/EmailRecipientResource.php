<?php

namespace App\Modules\Email\Filament\Resources;

use App\Modules\Email\Models\EmailRecipient;
use App\Modules\Email\Filament\Resources\EmailRecipientResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailRecipientResource extends Resource
{
    protected static ?string $model = EmailRecipient::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Email';

    protected static ?string $navigationLabel = 'Email Recipients';

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
            'index' => Pages\ListEmailRecipients::route('/'),
            'create' => Pages\CreateEmailRecipient::route('/create'),
            'edit' => Pages\EditEmailRecipient::route('/{record}/edit'),
        ];
    }
}
