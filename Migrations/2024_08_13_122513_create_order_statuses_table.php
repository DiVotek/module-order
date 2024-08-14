<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Seeders\OrderStatusSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(OrderStatus::getDb(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('email_template')->default('');
            $table->timestamps();
        });
        $seeder = new OrderStatusSeeder();
        $seeder->run();
    }

    public function down(): void
    {
        Schema::dropIfExists(OrderStatus::getDb());
    }
};
