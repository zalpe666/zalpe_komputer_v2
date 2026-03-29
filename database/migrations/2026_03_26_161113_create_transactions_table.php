<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice')->unique();
            $table->string('transaction_type');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();

            // 💰 harga
            $table->integer('subtotal');
            $table->integer('shipping_cost');
            $table->integer('total');

            // 🚚 pengiriman
            $table->string('courier_name');
            $table->string('courier_service');
            $table->string('estimated_delivery');

            // 💳 pembayaran
            $table->string('payment_method')->nullable();
            $table->enum('transaction_status', ['Pending', 'Paid', 'Packing', 'Sending','Delivered','Completed','Cancelled'])->default('pending');
            $table->string('notes')->nullable();
            $table->string('snap_token')->nullable(); // Midtrans

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
