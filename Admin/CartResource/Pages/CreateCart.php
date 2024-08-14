<?php

namespace Modules\Order\Admin\CartResource\Pages;

use Modules\Order\Admin\CartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCart extends CreateRecord
{
    protected static string $resource = CartResource::class;
}
