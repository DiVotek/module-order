<?php

namespace Modules\Order\Services\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusPending  implements PaymentStatusInterface
{
    public function getName(): string
    {
        return 'Pending';
    }
}
