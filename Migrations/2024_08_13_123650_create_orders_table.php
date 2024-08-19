<?php

use App\Models\StaticPage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Models\DeliveryMethod;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Models\PaymentMethod;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Order::getDb(), function (Blueprint $table) {
            $table->uuid();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignIdFor(OrderStatus::class, 'status');
            $table->json('user_data')->nullable();
            $table->json('products');
            $table->decimal('total', 10, 2);
            $table->string('currency', 5);
            $table->decimal('tax', 10, 2);
            $table->foreignIdFor(PaymentMethod::class);
            $table->foreignIdFor(DeliveryMethod::class);
            $table->string('payment_status', 20);
            $table->integer('payment_order_id')->nullable();
            $table->timestamps();
        });
        StaticPage::createSystemPage('Checkout', 'checkout', path: 'order::checkout-component');
        StaticPage::createSystemPage('Thank you', 'thank-you', path: 'order::thanks-component');
    }

    public function down(): void
    {
        Schema::dropIfExists(Order::getDb());
    }
};
