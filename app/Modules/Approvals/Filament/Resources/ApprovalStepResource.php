<?php

namespace App\Modules\Approvals\Filament\Resources;

use App\Modules\Approvals\Models\ApprovalStep;
use App\Modules\Approvals\Filament\Resources\ApprovalStepResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApprovalStepResource extends Resource
{
    protected static ?string $model = ApprovalStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Approvals';

    protected static ?string $navigationLabel = 'Approval Steps';

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
            'index' => Pages\ListApprovalSteps::route('/'),
            'create' => Pages\CreateApprovalStep::route('/create'),
            'edit' => Pages\EditApprovalStep::route('/{record}/edit'),
        ];
    }
}
