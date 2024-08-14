<?php

namespace Modules\Order\Admin;

use App\Services\Schema;
use App\Services\TableSchema;
use Filament\Forms\Components\Textarea;
use Modules\Order\Admin\OrderStatusResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\OrderStatus;

class OrderStatusResource extends Resource
{
    protected static ?string $model = OrderStatus::class;

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return !in_array($record->id, [
            OrderStatus::STATUS_NEW,
            OrderStatus::STATUS_PROCESSING,
            OrderStatus::STATUS_COMPLETED,
        ]);
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
        return __('Order status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Order statuses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Schema::getName(),
                Textarea::make('email_template')->label(__('Email template')),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TableSchema::getName(),
                TableSchema::getUpdatedAt(),
            ])
            ->filters([])
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
            'index' => Pages\ManageOrderStatuses::route('/'),
        ];
    }
}
