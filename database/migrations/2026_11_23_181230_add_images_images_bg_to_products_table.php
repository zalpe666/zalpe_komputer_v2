<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'images')) {
                $table->json('images')->nullable()->after('image');
            }

            if (!Schema::hasColumn('products', 'images_bg')) {
                $table->text('images_bg')->nullable()->after('images');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'images_bg')) {
                $table->dropColumn('images_bg');
            }

            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};
