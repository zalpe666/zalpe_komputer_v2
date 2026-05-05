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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('va_number')->nullable()->after('payment_status');
            $table->string('bank')->nullable()->after('va_number');
            $table->string('biller_code')->nullable()->after('bank'); // khusus mandiri
            $table->string('bill_key')->nullable()->after('biller_code'); // khusus mandiri
            $table->timestamp('expired_at')->nullable()->after('bill_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
