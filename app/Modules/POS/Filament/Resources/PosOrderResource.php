<?php

namespace App\Modules\POS\Filament\Resources;

use App\Modules\POS\Filament\Resources\PosOrderResource\Pages;
use App\Modules\POS\Models\PosOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PosOrderResource extends Resource
{
    protected static ?string $model = PosOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'POS';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('order_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\Select::make('pos_session_id')
                            ->relationship('session', 'session_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $product = \App\Modules\Inventory\Models\Product::find($state);
                                            if ($product) {
                                                $set('product_name', $product->name);
                                                $set('product_code', $product->code);
                                                $set('unit_price', $product->sale_price);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                        $set('total', $state * $get('unit_price'))),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                        $set('total', $state * $get('quantity'))),
                                Forms\Components\Select::make('discount_type')
                                    ->options([
                                        'fixed' => 'Fixed Amount',
                                        'percentage' => 'Percentage',
                                    ]),
                                Forms\Components\TextInput::make('discount_value')
                                    ->numeric(),
                                Forms\Components\TextInput::make('tax_rate')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0),
                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->readOnly(),
                            ])
                            ->columns(4)
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                        Forms\Components\Select::make('discount_type')
                            ->options([
                                'fixed' => 'Fixed Amount',
                                'percentage' => 'Percentage',
                            ]),
                        Forms\Components\TextInput::make('discount_value')
                            ->numeric(),
                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                    ])->columns(3),

                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\TextInput::make('amount_paid')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('amount_due')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                        Forms\Components\TextInput::make('change_amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->readOnly(),
                    ])->columns(3),

                Forms\Components\Section::make('Status & Notes')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'paid' => 'Paid',
                                'partially_paid' => 'Partially Paid',
                                'refunded' => 'Refunded',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'partially_paid' => 'Partially Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2),
                        Forms\Components\Textarea::make('customer_notes')
                            ->rows(2),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.session_number')
                    ->label('Session')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cashier')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'paid',
                        'warning' => 'partially_paid',
                        'info' => 'refunded',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'primary' => 'partially_paid',
                        'danger' => 'refunded',
                    ])
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'refunded' => 'Refunded',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\Filter::make('order_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('order_date', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('order_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosOrders::route('/'),
            'create' => Pages\CreatePosOrder::route('/create'),
            'edit' => Pages\EditPosOrder::route('/{record}/edit'),
        ];
    }
}
