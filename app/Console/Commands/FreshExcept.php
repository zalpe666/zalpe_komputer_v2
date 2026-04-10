<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class FreshExcept extends Command
{
    protected $signature = 'migrate:fresh:except 
                        {--except=* : Tabel yang tidak di-drop} 
                        {--seed : Jalankan seeder setelah migrate} 
                        {--seeder= : Seeder tertentu jika ingin spesifik}';

    protected $description = 'Run migrate:fresh tapi tidak menghapus tabel tertentu, bisa sekaligus seed';

    public function handle()
    {
        $exceptTables = $this->option('except');
        $runSeed = $this->option('seed');
        $specificSeeder = $this->option('seeder');

        $this->info('Tables excluded from fresh: ' . implode(', ', $exceptTables));

        // Ambil semua tabel dari information_schema
        $database = env('DB_DATABASE');
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$database]);
        $tables = collect($tables)->pluck('table_name')->toArray();

        // Filter tabel yang mau di-drop
        $tablesToDrop = array_diff($tables, $exceptTables);

        // Disable foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tablesToDrop as $table) {
            $this->info("Dropping table: $table");
            DB::statement("DROP TABLE IF EXISTS `$table`");
        }

        // Enable foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        // Jalankan seed jika opsi --seed diberikan
        if ($runSeed) {
            $this->info('Seeding database...');
            $seedOptions = ['--force' => true];
            if ($specificSeeder) {
                $seedOptions['--class'] = $specificSeeder;
            }
            Artisan::call('db:seed', $seedOptions);
            $this->info(Artisan::output());
        }

        $this->info('Done!');
    }
}