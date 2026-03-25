<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourierRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [];

        $districtZones = [

            // Jakarta Pusat (city_id: 1) - Sangat dekat dengan Kemayoran

            1 => ['base_price' => 8000, 'distance_factor' => 1.0], // Kemayoran (origin itself, maybe for local pickup rates?)

            2 => ['base_price' => 8000, 'distance_factor' => 1.0], // Cempaka Putih

            3 => ['base_price' => 8000, 'distance_factor' => 1.0], // Gambir

            4 => ['base_price' => 8000, 'distance_factor' => 1.0], // Johar Baru

            5 => ['base_price' => 8000, 'distance_factor' => 1.0], // Menteng

            6 => ['base_price' => 8000, 'distance_factor' => 1.0], // Sawah Besar

            7 => ['base_price' => 8000, 'distance_factor' => 1.0], // Senen

            8 => ['base_price' => 8000, 'distance_factor' => 1.0], // Tanah Abang



            // Jakarta Utara (city_id: 2) - Dekat-sedang

            9 => ['base_price' => 9000, 'distance_factor' => 1.1], // Kelapa Gading

            10 => ['base_price' => 10000, 'distance_factor' => 1.2], // Cilincing

            11 => ['base_price' => 9500, 'distance_factor' => 1.15], // Koja

            12 => ['base_price' => 9000, 'distance_factor' => 1.1], // Pademangan

            13 => ['base_price' => 9500, 'distance_factor' => 1.15], // Penjaringan

            14 => ['base_price' => 9000, 'distance_factor' => 1.1], // Tanjung Priok



            // Jakarta Selatan (city_id: 3) - Dekat-sedang

            15 => ['base_price' => 9000, 'distance_factor' => 1.1], // Kebayoran Baru

            16 => ['base_price' => 9500, 'distance_factor' => 1.15], // Cilandak

            17 => ['base_price' => 10000, 'distance_factor' => 1.2], // Jagakarsa

            18 => ['base_price' => 9000, 'distance_factor' => 1.1], // Kebayoran Lama

            19 => ['base_price' => 8500, 'distance_factor' => 1.05], // Mampang Prapatan

            20 => ['base_price' => 8500, 'distance_factor' => 1.05], // Pancoran

            21 => ['base_price' => 9000, 'distance_factor' => 1.1], // Pasar Minggu

            22 => ['base_price' => 9500, 'distance_factor' => 1.15], // Pesanggrahan

            23 => ['base_price' => 8500, 'distance_factor' => 1.05], // Setiabudi

            24 => ['base_price' => 8500, 'distance_factor' => 1.05], // Tebet



            // Jakarta Barat (city_id: 4) - Dekat-sedang

            25 => ['base_price' => 9000, 'distance_factor' => 1.1], // Palmerah

            26 => ['base_price' => 9500, 'distance_factor' => 1.15], // Cengkareng

            27 => ['base_price' => 8500, 'distance_factor' => 1.05], // Grogol Petamburan

            28 => ['base_price' => 10000, 'distance_factor' => 1.2], // Kalideres

            29 => ['base_price' => 9000, 'distance_factor' => 1.1], // Kebon Jeruk

            30 => ['base_price' => 9500, 'distance_factor' => 1.15], // Kembangan

            31 => ['base_price' => 8500, 'distance_factor' => 1.05], // Taman Sari

            32 => ['base_price' => 8500, 'distance_factor' => 1.05], // Tambora



            // Jakarta Timur (city_id: 5) - Dekat-sedang

            33 => ['base_price' => 9000, 'distance_factor' => 1.1], // Duren Sawit

            34 => ['base_price' => 9500, 'distance_factor' => 1.15], // Cakung

            35 => ['base_price' => 10000, 'distance_factor' => 1.2], // Cipayung

            36 => ['base_price' => 9500, 'distance_factor' => 1.15], // Ciracas

            37 => ['base_price' => 8500, 'distance_factor' => 1.05], // Jatinegara

            38 => ['base_price' => 8500, 'distance_factor' => 1.05], // Kramat Jati

            39 => ['base_price' => 9000, 'distance_factor' => 1.1], // Makasar

            40 => ['base_price' => 8500, 'distance_factor' => 1.05], // Matraman

            41 => ['base_price' => 9500, 'distance_factor' => 1.15], // Pasar Rebo

            42 => ['base_price' => 9000, 'distance_factor' => 1.1], // Pulo Gadung



            // Kota Bekasi (city_id: 6) - Sedang-jauh

            43 => ['base_price' => 10000, 'distance_factor' => 1.2], // Bekasi Timur

            44 => ['base_price' => 10000, 'distance_factor' => 1.2], // Bekasi Barat

            45 => ['base_price' => 10000, 'distance_factor' => 1.2], // Bekasi Selatan

            46 => ['base_price' => 10000, 'distance_factor' => 1.2], // Bekasi Utara

            47 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bantargebang

            48 => ['base_price' => 10500, 'distance_factor' => 1.25], // Jatiasih

            49 => ['base_price' => 10500, 'distance_factor' => 1.25], // Jatisampurna

            50 => ['base_price' => 10000, 'distance_factor' => 1.2], // Medansatria

            51 => ['base_price' => 10500, 'distance_factor' => 1.25], // Mustikajaya

            52 => ['base_price' => 10500, 'distance_factor' => 1.25], // Pondok Gede

            53 => ['base_price' => 10500, 'distance_factor' => 1.25], // Pondok Melati

            54 => ['base_price' => 10000, 'distance_factor' => 1.2], // Rawalumbu



            // Depok (city_id: 7) - Sedang-jauh

            55 => ['base_price' => 10500, 'distance_factor' => 1.25], // Beji

            56 => ['base_price' => 10500, 'distance_factor' => 1.25], // Sukmajaya

            57 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bojongsari

            58 => ['base_price' => 10500, 'distance_factor' => 1.25], // Cilodong

            59 => ['base_price' => 10500, 'distance_factor' => 1.25], // Cimanggis

            60 => ['base_price' => 11000, 'distance_factor' => 1.3], // Cinere

            61 => ['base_price' => 11000, 'distance_factor' => 1.3], // Cipayung

            62 => ['base_price' => 11000, 'distance_factor' => 1.3], // Limo

            63 => ['base_price' => 10500, 'distance_factor' => 1.25], // Pancoran Mas

            64 => ['base_price' => 11000, 'distance_factor' => 1.3], // Sawangan

            65 => ['base_price' => 11500, 'distance_factor' => 1.35], // Tapos



            // Kota Tangerang (city_id: 9) - Sedang-jauh

            66 => ['base_price' => 9500, 'distance_factor' => 1.15], // Cipondoh

            67 => ['base_price' => 9500, 'distance_factor' => 1.15], // Karawaci

            68 => ['base_price' => 10000, 'distance_factor' => 1.2], // Batuceper

            69 => ['base_price' => 10000, 'distance_factor' => 1.2], // Benda

            70 => ['base_price' => 9500, 'distance_factor' => 1.15], // Ciledug

            71 => ['base_price' => 10000, 'distance_factor' => 1.2], // Jatiuwung

            72 => ['base_price' => 9500, 'distance_factor' => 1.15], // Larangan

            73 => ['base_price' => 10000, 'distance_factor' => 1.2], // Neglasari

            74 => ['base_price' => 10000, 'distance_factor' => 1.2], // Periuk

            75 => ['base_price' => 9500, 'distance_factor' => 1.15], // Pinang

            76 => ['base_price' => 9000, 'distance_factor' => 1.1], // Tangerang

            77 => ['base_price' => 9500, 'distance_factor' => 1.15], // Cibodas



            // Kota Tangerang Selatan (city_id: 10) - Sedang-jauh

            78 => ['base_price' => 10000, 'distance_factor' => 1.2], // Serpong

            79 => ['base_price' => 10000, 'distance_factor' => 1.2], // Pamulang

            80 => ['base_price' => 10500, 'distance_factor' => 1.25], // Ciputat

            81 => ['base_price' => 10500, 'distance_factor' => 1.25], // Ciputat Timur

            82 => ['base_price' => 10000, 'distance_factor' => 1.2], // Pondok Aren

            83 => ['base_price' => 10000, 'distance_factor' => 1.2], // Serpong Utara

            84 => ['base_price' => 10500, 'distance_factor' => 1.25], // Setu



            // Kota Bogor (city_id: 8) - Jauh

            85 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bogor Utara

            86 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bogor Barat

            87 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bogor Selatan

            88 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bogor Tengah

            89 => ['base_price' => 11000, 'distance_factor' => 1.3], // Bogor Timur

            90 => ['base_price' => 11000, 'distance_factor' => 1.3], // Tanah Sareal

        ];

        foreach ($districtZones as $districtId => $zone) {

            $basePriceReguler = $zone['base_price'];

            $distanceFactor = $zone['distance_factor'];

            $now = now(); // ambil 1x saja



            // JNE

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'JNE',

                'service' => 'Reguler',

                'price_per_kg' => $basePriceReguler,

                'estimated_delivery_time' => '1-3 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'JNE',

                'service' => 'YES',

                'price_per_kg' => (int)($basePriceReguler * 1.50 * $distanceFactor),

                'estimated_delivery_time' => '1-2 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'JNE',

                'service' => 'Cargo',

                'price_per_kg' => (int)($basePriceReguler * 0.35),

                'estimated_delivery_time' => '3-4 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];



            // SiCepat

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'SiCepat',

                'service' => 'Reguler',

                'price_per_kg' => (int)($basePriceReguler * 1.05),

                'estimated_delivery_time' => '1-3 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'SiCepat',

                'service' => 'BEST',

                'price_per_kg' => (int)($basePriceReguler * 1.75 * $distanceFactor),

                'estimated_delivery_time' => '1 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'SiCepat',

                'service' => 'Cargo',

                'price_per_kg' => (int)($basePriceReguler * 0.35),

                'estimated_delivery_time' => '2-4 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];



            // Anteraja

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'Anteraja',

                'service' => 'Reguler',

                'price_per_kg' => (int)($basePriceReguler * 1.15),

                'estimated_delivery_time' => '1-2 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'Anteraja',

                'service' => 'Cargo',

                'price_per_kg' => (int)($basePriceReguler * 0.35),

                'estimated_delivery_time' => '3-4 Days',

                'created_at' => $now,

                'updated_at' => $now

            ];

            // GoSend

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'GoSend',

                'service' => 'Instant',

                'price_per_kg' => (int)($basePriceReguler * 1.25 * $distanceFactor * 1.5),

                'estimated_delivery_time' => '2-4 Hours',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'GoSend',

                'service' => 'Same Day',

                'price_per_kg' => (int)($basePriceReguler * 0.5 * $distanceFactor * 1.5),

                'estimated_delivery_time' => '12 Hours',

                'created_at' => $now,

                'updated_at' => $now

            ];



            // GrabExpress

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'GrabExpress',

                'service' => 'Instant',

                'price_per_kg' => (int)($basePriceReguler * 1.35 * $distanceFactor * 1.5),

                'estimated_delivery_time' => '2-4 Hours',

                'created_at' => $now,

                'updated_at' => $now

            ];

            $rates[] = [

                'district_id' => $districtId,

                'name' => 'GrabExpress',

                'service' => 'Same Day',

                'price_per_kg' => (int)($basePriceReguler * 0.75 * $distanceFactor * 1.5),

                'estimated_delivery_time' => '12 Hours',

                'created_at' => $now,

                'updated_at' => $now

            ];
        }

        DB::table('courier_rates')->insert($rates);
    }
}
