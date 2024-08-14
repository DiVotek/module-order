<?php

namespace Modules\Order\Services\Delivery;

use Filament\Forms\Components\TextInput;
use Modules\Order\Services\Interfaces\DeliveryMethodInterface;

class CityCourier implements DeliveryMethodInterface
{

   public function getId(): int
   {
      return 2;
   }

   public static function getSchema(): array
   {
      return [
         TextInput::make('city')->label(__('City'))
      ];
   }

   public function getFields(): array
   {
      return [
         'city' => 'required|string'
      ];
   }
}
