<?php

namespace App\Payment\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusPending  implements PaymentStatusInterface
{
    public function getName(): string
    {
        return 'Pending';
    }
}
