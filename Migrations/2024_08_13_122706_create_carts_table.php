<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Models\Cart;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(Cart::getDb(), function (Blueprint $table) {
            $table->uuid();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('products')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(Cart::getDb());
    }
};
