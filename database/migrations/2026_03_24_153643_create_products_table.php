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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic
            $table->string('name');
            $table->string('slug')->unique();

            // Relasi
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();


            $table->enum('type', ['Product', 'Games', 'Digital', 'Steam Wallet']);

            // Harga
            $table->integer('default_price');
            $table->integer('price')->nullable();
            $table->integer('discount')->default(0);

            // Gambar
            $table->string('image')->nullable();

            // Detail
            $table->integer('weight')->default(0);
            $table->text('description')->nullable();

            // Tambahan penting
            $table->integer('stock')->default(0);
            $table->integer('views')->default(0);
            $table->integer('sold')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
