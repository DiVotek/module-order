<?php

namespace Modules\Order\Admin\DeliveryMethodResource\Pages;

use Modules\Order\Admin\DeliveryMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryMethod extends EditRecord
{
    protected static string $resource = DeliveryMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
