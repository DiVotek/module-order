<?php

namespace Modules\Order\Admin\DeliveryMethodResource\Pages;

use Modules\Order\Admin\DeliveryMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDeliveryMethod extends CreateRecord
{
    protected static string $resource = DeliveryMethodResource::class;
}
