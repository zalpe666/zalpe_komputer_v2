<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class AddressDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Cek apakah user sudah punya alamat
            if ($user->addresses()->count() > 0) {
                continue;
            }

            // Buat 1-2 alamat dummy per user
            for ($i = 1; $i <= rand(1,2); $i++) {
                Address::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'phone' => '0812' . rand(10000000, 99999999),
                    'province_id' => 1, // contoh default
                    'city_id' => 1,     // contoh default
                    'district_id' => 1, // contoh default
                    'address' => 'Jl. Contoh Alamat No. ' . rand(1, 100),
                    'postal_code' => '12345',
                    'is_default' => $i === 1, // alamat pertama default
                ]);
            }
        }
    }
}