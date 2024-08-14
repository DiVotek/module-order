<?php

namespace App\Payment\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusSuccess  implements PaymentStatusInterface
{
    public function getName(): string
    {
        return 'Success';
    }
}
