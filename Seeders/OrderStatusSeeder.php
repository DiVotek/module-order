<?php

namespace Modules\Order\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        OrderStatus::insert([
            [
                'name' => 'New',
            ],
            [
                'name' => 'Processing',
            ],
            [
                'name' => 'Completed',
            ]
        ]);
    }
}
