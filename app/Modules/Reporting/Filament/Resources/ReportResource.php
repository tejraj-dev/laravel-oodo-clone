<?php

namespace App\Modules\Reporting\Filament\Resources;

use App\Modules\Reporting\Filament\Resources\ReportResource\Pages;
use App\Modules\Reporting\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Reporting';

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
                        Forms\Components\Textarea::make('description')
                            ->rows(3),
                        Forms\Components\Select::make('report_template_id')
                            ->relationship('template', 'name')
                            ->searchable()
                            ->preload(),
                    ])->columns(1),

                Forms\Components\Section::make('Report Configuration')
                    ->schema([
                        Forms\Components\Select::make('report_type')
                            ->options([
                                'tabular' => 'Tabular',
                                'chart' => 'Chart',
                                'pivot' => 'Pivot Table',
                                'summary' => 'Summary',
                                'custom' => 'Custom',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('data_source')
                            ->options([
                                'sales' => 'Sales',
                                'purchases' => 'Purchases',
                                'inventory' => 'Inventory',
                                'crm' => 'CRM',
                                'hr' => 'HR',
                                'accounting' => 'Accounting',
                                'pos' => 'POS',
                            ])
                            ->required(),
                        Forms\Components\Select::make('chart_type')
                            ->options([
                                'line' => 'Line Chart',
                                'bar' => 'Bar Chart',
                                'pie' => 'Pie Chart',
                                'doughnut' => 'Doughnut Chart',
                                'area' => 'Area Chart',
                                'scatter' => 'Scatter Plot',
                            ])
                            ->visible(fn (callable $get) => $get('report_type') === 'chart'),
                    ])->columns(3),

                Forms\Components\Section::make('Advanced Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('query_config')
                            ->label('Query Configuration')
                            ->keyLabel('Parameter')
                            ->valueLabel('Value'),
                        Forms\Components\KeyValue::make('filters')
                            ->label('Filters')
                            ->keyLabel('Field')
                            ->valueLabel('Condition'),
                        Forms\Components\KeyValue::make('chart_config')
                            ->label('Chart Configuration')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->visible(fn (callable $get) => $get('report_type') === 'chart'),
                    ])->columns(2)->collapsible(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_public')
                            ->label('Public Report'),
                        Forms\Components\Toggle::make('is_favorite')
                            ->label('Favorite'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_type')
                    ->badge()
                    ->colors([
                        'primary' => 'tabular',
                        'success' => 'chart',
                        'warning' => 'pivot',
                        'info' => 'summary',
                        'secondary' => 'custom',
                    ]),
                Tables\Columns\TextColumn::make('data_source')
                    ->badge(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public'),
                Tables\Columns\IconColumn::make('is_favorite')
                    ->boolean()
                    ->label('Favorite'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('report_type')
                    ->options([
                        'tabular' => 'Tabular',
                        'chart' => 'Chart',
                        'pivot' => 'Pivot Table',
                        'summary' => 'Summary',
                        'custom' => 'Custom',
                    ]),
                Tables\Filters\SelectFilter::make('data_source')
                    ->options([
                        'sales' => 'Sales',
                        'purchases' => 'Purchases',
                        'inventory' => 'Inventory',
                        'crm' => 'CRM',
                        'hr' => 'HR',
                        'accounting' => 'Accounting',
                        'pos' => 'POS',
                    ]),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Public'),
                Tables\Filters\TernaryFilter::make('is_favorite')
                    ->label('Favorite'),
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
