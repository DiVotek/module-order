<?php

namespace Modules\Order\Admin\DeliveryMethodResource\Pages;

use Modules\Order\Admin\DeliveryMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryMethods extends ListRecords
{
    protected static string $resource = DeliveryMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
