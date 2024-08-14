<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Models\OrderHistory;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(OrderHistory::getDb(), function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->string('order_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(OrderHistory::getDb());
    }
};
