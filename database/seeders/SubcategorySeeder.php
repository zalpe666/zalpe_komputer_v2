<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $subcategories = [
            // 1. PC Ready
            ['name' => 'PC Gaming', 'category_id' => 1],
            ['name' => 'PC Office', 'category_id' => 1],
            ['name' => 'PC Rendering', 'category_id' => 1],

            // 2. Notebook
            ['name' => 'Notebook Gaming', 'category_id' => 2],
            ['name' => 'Notebook Office', 'category_id' => 2],
            ['name' => 'Ultrabook', 'category_id' => 2],

            // 3. Processor
            ['name' => 'Intel Processor', 'category_id' => 3],
            ['name' => 'AMD Processor', 'category_id' => 3],

            // 4. Motherboard
            ['name' => 'Intel Motherboard', 'category_id' => 4],
            ['name' => 'AMD Motherboard', 'category_id' => 4],

            // 5. VGA
            ['name' => 'NVIDIA VGA', 'category_id' => 5],
            ['name' => 'AMD VGA', 'category_id' => 5],
        ];

        $dataToInsert = array_map(function ($item) use ($now) {
            return array_merge($item, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $subcategories);

        DB::table('subcategories')->insert($dataToInsert);
    }
}
