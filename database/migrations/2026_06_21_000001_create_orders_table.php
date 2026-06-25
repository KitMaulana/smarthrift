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
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('courier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('price', 12, 2);
            $table->decimal('service_fee', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('payment_method'); // qris, cod
            $table->string('status')->default('pending_payment'); // pending_payment, pending_shipping, shipped, delivered, completed, cancelled
            $table->text('shipping_address');
            $table->string('shipping_phone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
