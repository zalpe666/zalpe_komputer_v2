<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $brands = [
            ['name' => 'EK Gaming', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/001-EKGAMING.png'],
            ['name' => 'Cubegaming', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/002-cubegaming.png'],
            ['name' => 'Prime Gaming', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/Prime Gaming.png'],
            ['name' => '1stplayer', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/1stplayer.png'],
            ['name' => 'Adata', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/adata.png'],
            ['name' => 'AMD', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/AMD.png'],
            ['name' => 'Antec', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/antec.png'],
            ['name' => 'Arktek', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/arktek.png'],
            ['name' => 'Asrock', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/asrock.png'],
            ['name' => 'Asus', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/asus.png'],
            ['name' => 'Avexir', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/avexir.png'],
            ['name' => 'Be quiet!', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/be-quiet.png'],
            ['name' => 'Corsair', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/corsair.png'],
            ['name' => 'Deepcool', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/deepcool.png'],
            ['name' => 'Galax', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/galax.png'],
            ['name' => 'Geil', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/geil.png'],
            ['name' => 'Gigabyte', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/gigabyte.png'],
            ['name' => 'Gskill', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/gskill.png'],
            ['name' => 'Inno3D', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/inno3d.png'],
            ['name' => 'Intel', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/intel.png'],
            ['name' => 'Klevv', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/006-klevv.png'],
            ['name' => 'LG', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/lg.png'],
            ['name' => 'Lian-li', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/lian-li.png'],
            ['name' => 'Logitech', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/logitech.png'],
            ['name' => 'Maxsun', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/maxsun.png'],
            ['name' => 'Moza', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/moza.png'],
            ['name' => 'Msi', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/msi.png'],
            ['name' => 'Peladn', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/peladn.png'],
            ['name' => 'Razer', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/razer.png'],
            ['name' => 'Asus ROG', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/rog[asus].png'],
            ['name' => 'Samsung', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/samsung.png'],
            ['name' => 'Seasonic', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/seasonic.png'],
            ['name' => 'Super Flower', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/superflower.png'],
            ['name' => 'TP-Link', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/tplink.png'],
            ['name' => 'WDC', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/wdc.png'],
            ['name' => 'XFX', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/xfx.png'],
            ['name' => 'Zotac', 'photo_url' => 'https://enterkomputer.com/web-assets/frontend/brand/zotac.png'],
        ];

        // Memasukkan timestamp untuk setiap baris
        $dataToInsert = array_map(function ($item) use ($now) {
            return array_merge($item, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $brands);

        DB::table('brands')->insert($dataToInsert);
    }
}