<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Master User',
                'email' => 'master@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'master',
                'is_active' => true,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Cashier User',
                'email' => 'cashier@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'name' => 'Courier User',
                'email' => 'courier@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'courier',
                'is_active' => true,
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ],
        ]);
    }
}
