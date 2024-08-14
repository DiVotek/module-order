<?php

namespace Modules\Order\Services\Delivery;

use Modules\Order\Services\Interfaces\DeliveryMethodInterface;

class CountryDelivery implements DeliveryMethodInterface
{

   public function getId(): int
   {
      return 3;
   }

   public static function getSchema(): array
   {
      return [];
   }

   public function getFields(): array
   {
      return [
         'region' => 'required|string',
         'city' => 'required|string',
         'address' => 'required|string',
         'zip' => 'required|string',
      ];
   }
}
