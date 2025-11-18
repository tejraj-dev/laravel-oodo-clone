<?php

namespace App\Modules\POS\Filament\Resources;

use App\Modules\POS\Filament\Resources\PosPaymentResource\Pages;
use App\Modules\POS\Models\PosPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PosPaymentResource extends Resource
{
    protected static ?string $model = PosPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'POS';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('pos_order_id')
                            ->relationship('order', 'order_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('pos_session_id')
                            ->relationship('session', 'session_number')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('payment_method_id')
                            ->relationship('paymentMethod', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DateTimePicker::make('payment_date')
                            ->required()
                            ->default(now()),
                    ])->columns(2),

                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'mobile_money' => 'Mobile Money',
                                'bank_transfer' => 'Bank Transfer',
                                'credit' => 'Credit',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('reference_number')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Card Details')
                    ->schema([
                        Forms\Components\Select::make('card_type')
                            ->options([
                                'visa' => 'Visa',
                                'mastercard' => 'Mastercard',
                                'amex' => 'American Express',
                                'discover' => 'Discover',
                            ]),
                        Forms\Components\TextInput::make('card_last_four')
                            ->maxLength(4)
                            ->length(4),
                        Forms\Components\TextInput::make('transaction_id')
                            ->maxLength(255),
                    ])
                    ->columns(3)
                    ->visible(fn (callable $get) => $get('payment_method') === 'card'),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.session_number')
                    ->label('Session')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'card',
                        'warning' => 'mobile_money',
                        'info' => 'bank_transfer',
                        'danger' => 'credit',
                    ]),
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Processed By')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'card' => 'Card',
                        'mobile_money' => 'Mobile Money',
                        'bank_transfer' => 'Bank Transfer',
                        'credit' => 'Credit',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\Filter::make('payment_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('payment_date', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('payment_date', '<=', $date));
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
            ->defaultSort('payment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosPayments::route('/'),
            'create' => Pages\CreatePosPayment::route('/create'),
            'edit' => Pages\EditPosPayment::route('/{record}/edit'),
        ];
    }
}
