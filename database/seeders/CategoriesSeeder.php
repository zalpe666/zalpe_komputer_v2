<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $categories = [
            ['name' => 'PC Ready', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/pc-ready.svg'],
            ['name' => 'Notebook', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/notebook.svg'],
            ['name' => 'Processor', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/processor.svg'],
            ['name' => 'Motherboard', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/motherboard.svg'],
            ['name' => 'VGA', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/vga.svg'],
            ['name' => 'Hard Drive', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/harddisk.svg'],
            ['name' => 'SSD', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/ssd.svg'],
            ['name' => 'RAM', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/ram.svg'],
            ['name' => 'PSU', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/psu.svg'],
            ['name' => 'Casing', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/casing.svg'],
            ['name' => 'LCD', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/lcd.svg'],
            ['name' => 'Keyboard', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/keyboard.svg'],
            ['name' => 'Gaming Chair', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/gaming-chair.svg'],
            ['name' => 'Optical', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/optical.svg'],
            ['name' => 'Cooler', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/coolerfan.svg'],
            ['name' => 'Printer', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/printer.svg'],
            ['name' => 'Software', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/software.svg'],
            ['name' => 'Flash Drive', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/flashdrive.svg'],
            ['name' => 'Memory Card', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/memorycard.svg'],
            ['name' => 'UPS', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/ups.svg'],
            ['name' => 'Networking', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/networking.svg'],
            ['name' => 'All In One', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/aio.svg'],
            ['name' => 'Server', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/server.svg'],
            ['name' => 'Headset', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/headset.svg'],
            ['name' => 'Projector', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/projector.svg'],
            ['name' => 'Soundcard', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/soundcard.svg'],
            ['name' => 'Speaker', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/speaker.svg'],
            ['name' => 'Accessories', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/accessories.svg'],
            ['name' => 'Drawer', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/drawingtablet.svg'],
            ['name' => 'Gadget', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/gadget.svg'],
            ['name' => 'Notebook Accessories', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/notebook-accessories.svg'],
            ['name' => 'Digital', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/icon/svg/category/notebook-accessories.svg'],
        ];

        // Tambahkan timestamp untuk setiap baris
        $dataToInsert = array_map(function ($item) use ($now) {
            return array_merge($item, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $categories);

        DB::table('categories')->insert($dataToInsert);
    }
}
