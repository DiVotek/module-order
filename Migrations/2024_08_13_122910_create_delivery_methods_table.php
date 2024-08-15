<?php

use App\Services\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Models\DeliveryMethod;
use Modules\Order\Seeders\DeliveryMethodSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(DeliveryMethod::getDb(), function (Blueprint $table) {
            $table->id();
            $table->bigInteger('delivery_method_id');
            $table->string('name');
            $table->boolean('status')->default(Status::OFF);
            $table->integer('sorting')->default(0);
            $table->string('image')->nullable();
            $table->double('price')->default(0);
            $table->double('free_from')->default(500);
            $table->json('settings')->nullable();
            $table->json('fields')->nullable();
            $table->timestamps();
        });

        (new DeliveryMethodSeeder())->run();
    }

    public function down(): void
    {
        Schema::dropIfExists(DeliveryMethod::getDb());
    }
};
