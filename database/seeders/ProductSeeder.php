<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Product', 'Games', 'Digital', 'Steam Wallet'];

        for ($i = 1; $i <= 20; $i++) {

            $name = 'Product ' . $i;

            DB::table('products')->insert([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . uniqid(),
                'category_id' => rand(1, 10),
                'brand_id' => rand(1, 10),
                'type' => $types[array_rand($types)], // ✅ ini yang penting
                'default_price' => rand(100000, 5000000),
                'stock' => rand(1, 100),
                'image' => 'https://images.tokopedia.net/img/cache/300/VqbcmM/2024/12/14/2343e461-2a86-4384-a3fd-3b0359bc1e46.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}