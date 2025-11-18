<?php

namespace App\Modules\Sales\Filament\Resources;

use App\Modules\Sales\Filament\Resources\QuoteResource\Pages;
use App\Modules\Sales\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
