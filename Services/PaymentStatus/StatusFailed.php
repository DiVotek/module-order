<?php

namespace App\Payment\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusFailed  implements PaymentStatusInterface
{
    public function getName(): string
    {
        return 'Failed';
    }
}
