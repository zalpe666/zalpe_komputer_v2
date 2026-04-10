<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Banner::insert([
            [
                'image' => 'https://enterkomputer.com/web-assets/frontend/banner/home/Banner-Slider-Home-PC-new-website-1761818829.jpg',
                'link' => '#',
            ],
            [
                'image' => 'https://enterkomputer.com/web-assets/frontend/banner/home/Gigabyte-AORUS-AMD-CAP-banner-03-26-1774939600.jpg',
                'link' => '#',
            ],
            [
                'image' => 'https://enterkomputer.com/web-assets/frontend/banner/home/Banner-Slider-Home-PC-9060xt-1-1774945539.jpg',
                'link' => '#',
            ],
            [
                'image' => 'https://enterkomputer.com/web-assets/frontend/banner/home/Banner-Slider-Home-Casing-cgcinema-1-1774850358.jpg',
                'link' => '#',
            ],
        ]);
    }
}
