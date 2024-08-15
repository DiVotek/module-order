<?php

namespace Modules\Order\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::query()->create([
            'payment_id' => 1,
            'name' => 'Cash',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
        PaymentMethod::query()->create([
            'payment_id' => 7,
            'name' => 'PayPal',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
        PaymentMethod::query()->create([
            'payment_id' => 3,
            'name' => 'MonoPay',
            'comission' => 0,
            'settings' => [],
            'fields' => []
        ]);
    }
}
