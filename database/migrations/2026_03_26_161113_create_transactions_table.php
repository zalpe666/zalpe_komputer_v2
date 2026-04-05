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
            $table->enum('transaction_type', ['Shopping', 'Games', 'Top-Up', 'Phone Credit'])->default('Shopping');


            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->cascadeOnDelete();

            // 💰 harga
            $table->integer('subtotal');
            $table->integer('shipping_cost');
            $table->integer('discount_by_merchant')->default(0);
            $table->integer('discount_by_voucher')->default(0);
            $table->integer('total');
            
            // 🚚 pengiriman
            $table->string('courier_name');
            $table->string('courier_service');
            $table->string('estimated_delivery');
            $table->integer('total_weight')->default(0); // 🔥 total berat untuk kurir

            // 💳 pembayaran
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['Pending', 'Unpaid', 'Paid', 'Refunded'])->default('pending');
            $table->enum('transaction_status', ['Pending', 'Waiting Payment', 'Packing', 'Sending', 'Delivered', 'Completed', 'Cancelled'])->default('Pending');
            $table->string('notes')->nullable();
            $table->string('snap_token')->nullable(); // Midtrans

            $table->timestamp('payment_date')->nullable();
            $table->timestamp('sending_date')->nullable();
            $table->timestamp('delivered_date')->nullable();
            $table->timestamp('canceled_date')->nullable();
            $table->timestamp('completed_date')->nullable();
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
