<?php

namespace Modules\Order\Services\Delivery;

use Modules\Order\Services\Interfaces\DeliveryMethodInterface;

class Pickup implements DeliveryMethodInterface
{

   public function getId(): int
   {
      return 1;
   }

   public static function getSchema(): array
   {
      return [];
   }

   public function getFields(): array
   {
      return [];
   }
}
