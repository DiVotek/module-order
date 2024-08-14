<?php

namespace Modules\Order\Admin\CartResource\Pages;

use Modules\Order\Admin\CartResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarts extends ListRecords
{
    protected static string $resource = CartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
