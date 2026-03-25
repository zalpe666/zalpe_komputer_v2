<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('products')->insert([
            [
                'id' => 61,
                'name' => 'Corsair NAUTILUS 360 RS ARGB Liquid CPU Cooler',
                'slug' => 'corsair-nautilus-360-rs-argb-liquid-cpu-cooler',
                'category_id' => 15,
                'brand_id' => 13,
                'type' => 'Product',
                'default_price' => 1740000,
                'price' => null,
                'discount' => 0,
                'image' => 'https://images.tokopedia.net/img/cache/900/VqbcmM/2024/11/18/2e1df4e8-3a1a-4cf3-956f-735b57324a33.jpg',
                'weight' => 0,
                'description' => null,
                'stock' => 10,
                'views' => 0,
                'sold' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 62,
                'name' => '1STPLAYER Gaming K8 Fire Dancing Kit (Keyboard + Mouse)',
                'slug' => '1stplayer-gaming-k8-fire-dancing-kit-keyboard-mouse',
                'category_id' => 12,
                'brand_id' => 4,
                'type' => 'Product',
                'default_price' => 250000,
                'price' => null,
                'discount' => 0,
                'image' => 'https://ecs7.tokopedia.net/img/cache/700/product-1/2019/12/10/83419695/83419695_c7910a79-f841-48e8-b18b-14d753fe6c19_800_800',
                'weight' => 0,
                'description' => null,
                'stock' => 100,
                'views' => 0,
                'sold' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 63,
                'name' => 'Logitech G102 V2 Lightsync Gaming Mouse',
                'slug' => 'logitech-g102-v2-lightsync-gaming-mouse',
                'category_id' => 12,
                'brand_id' => 24,
                'type' => 'Product',
                'default_price' => 500000,
                'price' => null,
                'discount' => 0,
                'image' => 'https://ecs7.tokopedia.net/img/cache/700/VqbcmM/2021/10/29/bd8df6d7-af3f-4812-a95f-6c3a8f65ae04.jpg',
                'weight' => 0,
                'description' => null,
                'stock' => 10,
                'views' => 0,
                'sold' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 64,
                'name' => 'Steam Wallet 60.000',
                'slug' => 'steam-wallet-60000',
                'category_id' => 32,
                'brand_id' => 38,
                'type' => 'Steam Wallet',
                'default_price' => 59000,
                'price' => null,
                'discount' => 0,
                'image' => 'https://cdn.unipin.com/images/icon_product_pages/1757435751-icon-Steam%20Wallet_14_11zon.png',
                'weight' => 0,
                'description' => null,
                'stock' => 100,
                'views' => 0,
                'sold' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}