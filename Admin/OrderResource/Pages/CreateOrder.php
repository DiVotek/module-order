<?php

namespace Modules\Order\Admin\OrderResource\Pages;

use Modules\Order\Admin\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
