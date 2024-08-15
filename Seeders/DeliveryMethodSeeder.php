<?php

namespace Modules\Order\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Models\DeliveryMethod;

class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        DeliveryMethod::query()->create([
            'delivery_method_id' => 1,
            'name' => 'Pickup',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
        DeliveryMethod::query()->create([
            'delivery_method_id' => 2,
            'name' => 'City Courier',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
        DeliveryMethod::query()->create([
            'delivery_method_id' => 3,
            'name' => 'Country Delivery',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
    }
}
