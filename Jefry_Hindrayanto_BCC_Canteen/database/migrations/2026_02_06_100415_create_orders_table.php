<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('canteen_id')->constrained()->onDelete('cascade');

            $table->integer('total_price')->default(0);

            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('order_status', ['waiting', 'cooking', 'ready', 'completed'])->default('waiting');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
