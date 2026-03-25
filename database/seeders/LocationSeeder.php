<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinces')->insert([
            ['id' => 1, 'name' => 'DKI Jakarta'],
            ['id' => 2, 'name' => 'Jawa Barat'],
            ['id' => 3, 'name' => 'Banten'],
        ]);

        DB::table('cities')->insert([
            ['id' => 1, 'province_id' => 1, 'name' => 'Jakarta Pusat'],
            ['id' => 2, 'province_id' => 1, 'name' => 'Jakarta Utara'],
            ['id' => 3, 'province_id' => 1, 'name' => 'Jakarta Selatan'],
            ['id' => 4, 'province_id' => 1, 'name' => 'Jakarta Barat'],
            ['id' => 5, 'province_id' => 1, 'name' => 'Jakarta Timur'],
            ['id' => 6, 'province_id' => 2, 'name' => 'Kota Bekasi'],
            ['id' => 7, 'province_id' => 2, 'name' => 'Depok'],
            ['id' => 8, 'province_id' => 2, 'name' => 'Kota Bogor'],
            ['id' => 9, 'province_id' => 3, 'name' => 'Kota Tangerang'],
            ['id' => 10, 'province_id' => 3, 'name' => 'Kota Tangerang Selatan'],
        ]);

        DB::table('districts')->insert([

            // Jakarta Pusat (city_id: 1)

            ['id' => 1, 'city_id' => 1, 'name' => 'Kemayoran'],

            ['id' => 2, 'city_id' => 1, 'name' => 'Cempaka Putih'],

            ['id' => 3, 'city_id' => 1, 'name' => 'Gambir'],

            ['id' => 4, 'city_id' => 1, 'name' => 'Johar Baru'],

            ['id' => 5, 'city_id' => 1, 'name' => 'Menteng'],

            ['id' => 6, 'city_id' => 1, 'name' => 'Sawah Besar'],

            ['id' => 7, 'city_id' => 1, 'name' => 'Senen'],

            ['id' => 8, 'city_id' => 1, 'name' => 'Tanah Abang'],



            // Jakarta Utara (city_id: 2)

            ['id' => 9, 'city_id' => 2, 'name' => 'Kelapa Gading'],

            ['id' => 10, 'city_id' => 2, 'name' => 'Cilincing'],

            ['id' => 11, 'city_id' => 2, 'name' => 'Koja'],

            ['id' => 12, 'city_id' => 2, 'name' => 'Pademangan'],

            ['id' => 13, 'city_id' => 2, 'name' => 'Penjaringan'],

            ['id' => 14, 'city_id' => 2, 'name' => 'Tanjung Priok'],



            // Jakarta Selatan (city_id: 3)

            ['id' => 15, 'city_id' => 3, 'name' => 'Kebayoran Baru'],

            ['id' => 16, 'city_id' => 3, 'name' => 'Cilandak'],

            ['id' => 17, 'city_id' => 3, 'name' => 'Jagakarsa'],

            ['id' => 18, 'city_id' => 3, 'name' => 'Kebayoran Lama'],

            ['id' => 19, 'city_id' => 3, 'name' => 'Mampang Prapatan'],

            ['id' => 20, 'city_id' => 3, 'name' => 'Pancoran'],

            ['id' => 21, 'city_id' => 3, 'name' => 'Pasar Minggu'],

            ['id' => 22, 'city_id' => 3, 'name' => 'Pesanggrahan'],

            ['id' => 23, 'city_id' => 3, 'name' => 'Setiabudi'],

            ['id' => 24, 'city_id' => 3, 'name' => 'Tebet'],



            // Jakarta Barat (city_id: 4)

            ['id' => 25, 'city_id' => 4, 'name' => 'Palmerah'],

            ['id' => 26, 'city_id' => 4, 'name' => 'Cengkareng'],

            ['id' => 27, 'city_id' => 4, 'name' => 'Grogol Petamburan'],

            ['id' => 28, 'city_id' => 4, 'name' => 'Kalideres'],

            ['id' => 29, 'city_id' => 4, 'name' => 'Kebon Jeruk'],

            ['id' => 30, 'city_id' => 4, 'name' => 'Kembangan'],

            ['id' => 31, 'city_id' => 4, 'name' => 'Taman Sari'],

            ['id' => 32, 'city_id' => 4, 'name' => 'Tambora'],



            // Jakarta Timur (city_id: 5)

            ['id' => 33, 'city_id' => 5, 'name' => 'Duren Sawit'],

            ['id' => 34, 'city_id' => 5, 'name' => 'Cakung'],

            ['id' => 35, 'city_id' => 5, 'name' => 'Cipayung'],

            ['id' => 36, 'city_id' => 5, 'name' => 'Ciracas'],

            ['id' => 37, 'city_id' => 5, 'name' => 'Jatinegara'],

            ['id' => 38, 'city_id' => 5, 'name' => 'Kramat Jati'],

            ['id' => 39, 'city_id' => 5, 'name' => 'Makasar'],

            ['id' => 40, 'city_id' => 5, 'name' => 'Matraman'],

            ['id' => 41, 'city_id' => 5, 'name' => 'Pasar Rebo'],

            ['id' => 42, 'city_id' => 5, 'name' => 'Pulo Gadung'],



            // Kota Bekasi (city_id: 6)

            ['id' => 43, 'city_id' => 6, 'name' => 'Bekasi Timur'],

            ['id' => 44, 'city_id' => 6, 'name' => 'Bekasi Barat'],

            ['id' => 45, 'city_id' => 6, 'name' => 'Bekasi Selatan'],

            ['id' => 46, 'city_id' => 6, 'name' => 'Bekasi Utara'],

            ['id' => 47, 'city_id' => 6, 'name' => 'Bantargebang'],

            ['id' => 48, 'city_id' => 6, 'name' => 'Jatiasih'],

            ['id' => 49, 'city_id' => 6, 'name' => 'Jatisampurna'],

            ['id' => 50, 'city_id' => 6, 'name' => 'Medansatria'],

            ['id' => 51, 'city_id' => 6, 'name' => 'Mustikajaya'],

            ['id' => 52, 'city_id' => 6, 'name' => 'Pondok Gede'],

            ['id' => 53, 'city_id' => 6, 'name' => 'Pondok Melati'],

            ['id' => 54, 'city_id' => 6, 'name' => 'Rawalumbu'],



            // Depok (city_id: 7)

            ['id' => 55, 'city_id' => 7, 'name' => 'Beji'],

            ['id' => 56, 'city_id' => 7, 'name' => 'Sukmajaya'],

            ['id' => 57, 'city_id' => 7, 'name' => 'Bojongsari'],

            ['id' => 58, 'city_id' => 7, 'name' => 'Cilodong'],

            ['id' => 59, 'city_id' => 7, 'name' => 'Cimanggis'],

            ['id' => 60, 'city_id' => 7, 'name' => 'Cinere'],

            ['id' => 61, 'city_id' => 7, 'name' => 'Cipayung'],

            ['id' => 62, 'city_id' => 7, 'name' => 'Limo'],

            ['id' => 63, 'city_id' => 7, 'name' => 'Pancoran Mas'],

            ['id' => 64, 'city_id' => 7, 'name' => 'Sawangan'],

            ['id' => 65, 'city_id' => 7, 'name' => 'Tapos'],



            // Kota Tangerang (city_id: 9)

            ['id' => 66, 'city_id' => 9, 'name' => 'Cipondoh'],

            ['id' => 67, 'city_id' => 9, 'name' => 'Karawaci'],

            ['id' => 68, 'city_id' => 9, 'name' => 'Batuceper'],

            ['id' => 69, 'city_id' => 9, 'name' => 'Benda'],

            ['id' => 70, 'city_id' => 9, 'name' => 'Ciledug'],

            ['id' => 71, 'city_id' => 9, 'name' => 'Jatiuwung'],

            ['id' => 72, 'city_id' => 9, 'name' => 'Larangan'],

            ['id' => 73, 'city_id' => 9, 'name' => 'Neglasari'],

            ['id' => 74, 'city_id' => 9, 'name' => 'Periuk'],

            ['id' => 75, 'city_id' => 9, 'name' => 'Pinang'],

            ['id' => 76, 'city_id' => 9, 'name' => 'Tangerang'],

            ['id' => 77, 'city_id' => 9, 'name' => 'Cibodas'],



            // Kota Tangerang Selatan (city_id: 10)

            ['id' => 78, 'city_id' => 10, 'name' => 'Serpong'],

            ['id' => 79, 'city_id' => 10, 'name' => 'Pamulang'],

            ['id' => 80, 'city_id' => 10, 'name' => 'Ciputat'],

            ['id' => 81, 'city_id' => 10, 'name' => 'Ciputat Timur'],

            ['id' => 82, 'city_id' => 10, 'name' => 'Pondok Aren'],

            ['id' => 83, 'city_id' => 10, 'name' => 'Serpong Utara'],

            ['id' => 84, 'city_id' => 10, 'name' => 'Setu'],



            // Kota Bogor (city_id: 8)

            ['id' => 85, 'city_id' => 8, 'name' => 'Bogor Utara'],

            ['id' => 86, 'city_id' => 8, 'name' => 'Bogor Barat'],

            ['id' => 87, 'city_id' => 8, 'name' => 'Bogor Selatan'],

            ['id' => 88, 'city_id' => 8, 'name' => 'Bogor Tengah'],

            ['id' => 89, 'city_id' => 8, 'name' => 'Bogor Timur'],

            ['id' => 90, 'city_id' => 8, 'name' => 'Tanah Sareal'],

        ]);
    }
}
