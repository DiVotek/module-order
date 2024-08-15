<?php

namespace Modules\Order\Admin;

use App\Services\Helper;
use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Order\Admin\DeliveryMethodResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\DeliveryMethod;

class DeliveryMethodResource extends Resource
{
    protected static ?string $model = DeliveryMethod::class;

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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return __('Delivery method');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Delivery methods');
    }

    public static function form(Form $form): Form
    {
        $schema = [
            Schema::getName(),
            Schema::getStatus(),
            Schema::getSorting(),
            TextInput::make('price')->translateLabel()->numeric()->suffix(app('currency')->code)->default(0),
            TextInput::make('free_from')->translateLabel()->numeric()->suffix(app('currency')->code)->default(500),
            Schema::getImage(),
        ];
        $settings = Helper::getDeliveryOptions($form->model->delivery_method_id);
        if ($settings) {
            $schema[] = Section::make('Settings')->schema($settings);
        }
        return $form
            ->schema([
                Section::make()->schema($schema)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TableSchema::getName(),
                TableSchema::getStatus(),
                TableSchema::getSorting(),
                TableSchema::getPrice(),
            ])
            ->reorderable('sorting')
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
            ])->headerActions([
                Tables\Actions\Action::make('Settings')
                    ->slideOver()
                    ->icon('heroicon-o-cog')
                    ->modal()
                    ->fillForm(function (): array {
                        return [
                            'method' => setting(config('settings.delivery.default'), ''),
                        ];
                    })
                    ->action(function (array $data): void {
                        setting([
                            config('settings.delivery.default') => $data['method'] ?? 0,
                        ]);
                    })
                    ->form(function ($form) {
                        return $form
                            ->schema([
                                Section::make('')->schema([
                                    Select::make('method')
                                        ->label(__('Default payment method'))
                                        ->native(false)
                                        ->options(DeliveryMethod::pluck('name', 'id')->toArray()),
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
            'index' => Pages\ListDeliveryMethods::route('/'),
            'create' => Pages\CreateDeliveryMethod::route('/create'),
            'edit' => Pages\EditDeliveryMethod::route('/{record}/edit'),
        ];
    }
}
