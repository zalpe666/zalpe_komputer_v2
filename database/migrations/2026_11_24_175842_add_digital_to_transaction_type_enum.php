<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY transaction_type
            ENUM('Shopping', 'Top-Up', 'Games', 'Phone Credit', 'Digital')
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE transactions
            MODIFY transaction_type
            ENUM('Shopping', 'Top-Up', 'Games', 'Phone Credit')
            NOT NULL
        ");
    }
};