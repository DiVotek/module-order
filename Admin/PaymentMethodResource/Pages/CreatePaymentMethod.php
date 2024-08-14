<?php

namespace Modules\Order\Admin\PaymentMethodResource\Pages;

use Modules\Order\Admin\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
