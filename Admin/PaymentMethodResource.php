<?php

namespace Modules\Order\Admin;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Modules\Order\Admin\PaymentMethodResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\PaymentMethod;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->withoutGlobalScopes()->count();
    }

    public static function getModelLabel(): string
    {
        return __('Payment method');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Payment methods');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Settings')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->modal()
                    ->fillForm(function (): array {
                        return [
                            'payment_method' => setting(config('settings.payment.default'),''),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.payment.default') => $data['payment_method'] ?? 0,
                        ]);
                    })
                    ->form(function ($form) {
                        return $form
                            ->schema([
                                Section::make('')->schema([
                                    Select::make('payment_method')
                                        ->label(__('Default payment method'))
                                        ->native(false)
                                        ->options(PaymentMethod::pluck('name', 'id')->toArray()),
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
