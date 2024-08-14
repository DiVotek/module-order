<?php

namespace App\Payment\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusUnexpected  implements PaymentStatusInterface
{
    public function getName(): string
    {
        return 'Unexpected';
    }
}
