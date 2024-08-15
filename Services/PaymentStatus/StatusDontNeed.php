<?php

namespace Modules\Order\Services\PaymentStatus;

use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class StatusDontNeed  implements PaymentStatusInterface
{
   public function getName(): string
   {
      return ' - ';
   }
}
