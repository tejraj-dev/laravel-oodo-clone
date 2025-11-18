<?php

namespace App\Modules\Approvals\Filament\Resources;

use App\Modules\Approvals\Models\ApprovalDelegate;
use App\Modules\Approvals\Filament\Resources\ApprovalDelegateResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApprovalDelegateResource extends Resource
{
    protected static ?string $model = ApprovalDelegate::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Approvals';

    protected static ?string $navigationLabel = 'Approval Delegates';

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
            'index' => Pages\ListApprovalDelegates::route('/'),
            'create' => Pages\CreateApprovalDelegate::route('/create'),
            'edit' => Pages\EditApprovalDelegate::route('/{record}/edit'),
        ];
    }
}
