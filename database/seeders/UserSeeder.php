<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $password = bcrypt('password');

        // 🔹 User default
        User::insert([
            [
                'name' => 'Master User',
                'email' => 'master@gmail.com',
                'password' => $password,
                'role' => 'master',
                'is_active' => true,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Cashier User',
                'email' => 'cashier@gmail.com',
                'password' => $password,
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'name' => 'Courier User',
                'email' => 'courier@gmail.com',
                'password' => $password,
                'role' => 'courier',
                'is_active' => true,
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@gmail.com',
                'password' => $password,
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Zalpe Rabbani',
                'email' => 'zalpe@gmail.com',
                'password' => $password,
                'role' => 'customer',
                'is_active' => true,
            ],
        ]);

        // 🔥 10 Customer Random
        $names = [
            'Budi Santoso',
            'Andi Pratama',
            'Rina Sari',
            'Dewi Lestari',
            'Agus Saputra',
            'Siti Nurhaliza',
            'Fajar Nugroho',
            'Putri Maharani',
            'Rizky Hidayat',
            'Dian Kusuma'
        ];

        foreach ($names as $i => $name) {
            User::create([
                'name' => $name,
                'email' => 'user' . ($i + 1) . '@gmail.com',
                'password' => $password,
                'role' => 'customer',
                'is_active' => true,
            ]);
        }
    }
}
