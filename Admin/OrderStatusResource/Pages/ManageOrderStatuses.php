<?php

namespace Modules\Order\Admin\OrderStatusResource\Pages;

use Modules\Order\Admin\OrderStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrderStatuses extends ManageRecords
{
    protected static string $resource = OrderStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
