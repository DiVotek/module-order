<?php

namespace Modules\Order\Admin;

use App\Models\StaticPage;
use App\Services\Schema;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Modules\Order\Admin\OrderResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->withoutGlobalScopes()->count();
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    KeyValue::make('user_data')->label(__('Name'))->deletable(false)->addable(false)->editableKeys(false),
                    Select::make('status')->relationship('order_status', 'name')->label(__('Status')),
                    Repeater::make('products')->schema([
                        Select::make('id')->options(Product::query()->pluck('name', 'id')->toArray())->label(__('Product')),
                        TextInput::make('price')->readOnly()->suffix(app('currency')->code)->label(__('Price')),
                        TextInput::make('sku')->readOnly(),
                    ])->label(__('Products')),
                ])
                //
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->orderByDesc('created_at');
            })
            ->columns([
                TextColumn::make('user_data.name')->label(__('Name')),
                TextColumn::make('user_data.phone')->label(__('Phone')),
                TextColumn::make('products')
                    ->badge()
                    ->translateLabel()
                    ->getStateUsing(function ($record) {
                        return is_array($record->products) ? count($record->products) : 0;
                    }),
                TextColumn::make('total')->label(__('Total'))->money(app('currency')->code),
                TextColumn::make('order_status.name')->label(__('Status')),
                TextColumn::make('paymentMethod.name'),
                TextColumn::make('payment_status')->label(__('Payment status')),
                TextColumn::make('deliveryMethod.name'),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                Tables\Actions\Action::make('Settings')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->modal()
                    ->fillForm(function (): array {
                        return [
                            'min_orice' => setting(config('settings.min_order_price'), ''),
                            'email_to' => setting(config('settings.email_to'), ''),
                            'design' => setting(config('settings.order.design'), ''),
                            'policy' => setting(config('settings.order.policy'), ''),
                            'is_name' => setting(config('settings.order.fields.name'), false),
                            'is_surname' => setting(config('settings.order.fields.surname'), false),
                            'is_email' => setting(config('settings.order.fields.email'), false),
                            'is_phone' => setting(config('settings.order.fields.phone'), false),
                            'is_comment' => setting(config('settings.order.fields.comment'), false),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.min_order_price') => $data['min_orice'] ?? 0,
                            config('settings.email_to') => $data['email_to'] ?? '',
                            config('settings.order.design') => $data['design'] ?? '',
                            config('settings.order.policy') => $data['policy'] ?? '',
                            config('settings.order.fields.name') => $data['is_name'] ?? false,
                            config('settings.order.fields.surname') => $data['is_surname'] ?? false,
                            config('settings.order.fields.email') => $data['is_email'] ?? false,
                            config('settings.order.fields.phone') => $data['is_phone'] ?? false,
                            config('settings.order.fields.comment') => $data['is_comment'] ?? false,
                        ]);
                    })
                    ->form(function ($form) {
                        return $form
                            ->schema([
                                Section::make('')->schema([
                                    TextInput::make('min_orice')
                                        ->label(__('Minimum order price'))
                                        ->numeric()
                                        ->suffix(app('currency')->code)
                                        ->default(setting(config('settings.min_order_price'), 0)),
                                    TextInput::make('email_to')->email()->label(__('Send email to'))->default(setting(config('settings.email_to'), '')),
                                    Schema::getModuleTemplateSelect('Pages/Order'),
                                    Schema::getSelect('policy', StaticPage::query()->pluck('name', 'id')->toArray()),
                                    Toggle::make('is_name')->label(__('Name'))->default(setting(config('settings.order.fields.name'), false)),
                                    Toggle::make('is_surname')->label(__('Surname'))->default(setting(config('settings.order.fields.surname'), false)),
                                    Toggle::make('is_email')->label(__('Email'))->default(setting(config('settings.order.fields.email'), false)),
                                    Toggle::make('is_phone')->label(__('Phone'))->default(setting(config('settings.order.fields.phone'), false)),
                                    Toggle::make('is_comment')->label(__('Comment'))->default(setting(config('settings.order.fields.comment'), false)),
                                ]),
                            ]);
                    })
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}/view'),
        ];
    }
}
