<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::with('addresses')->get();
        $products = Product::all();

        foreach ($users as $user) {

            $date = date('dmY');
            $time = date('His');
            $randomCode = rand(100, 999); // integer 3 digit
            $invoice = "ZK/{$date}/{$time}/{$randomCode}";

            // Ambil 2-3 product random
            $selectedProducts = $products->random(rand(2, 3));

            // Pilih salah satu alamat user
            $address = $user->addresses->random();

            // Hitung subtotal
            $subtotal = $selectedProducts->sum(function ($product) {
                $qty = rand(1, 3);
                return $product->final_price * $qty;
            });

            $shipping_cost = rand(10000, 50000);
            $total = $subtotal + $shipping_cost;

            // Buat transaction
            $transaction = Transaction::create([
                'invoice' => $invoice,
                'user_id' => $user->id,
                'address_id' => $address->id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'courier_name' => 'JNE',
                'courier_service' => 'Reguler',
                'estimated_delivery' => '2-3 Hari',
                'payment_method' => ['VA', 'QRIS', 'E-Wallet'][array_rand(['VA', 'QRIS', 'E-Wallet'])],
                'status' => "Paid",
                'notes' => null,
                'snap_token' => null,
                'transaction_type' => 'Online',
            ]);

            // Buat transaction details
            foreach ($selectedProducts as $product) {
                $qty = rand(1, 3);
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'price' => $product->final_price,
                    'qty' => $qty,
                    'total' => $product->final_price * $qty,
                ]);
            }
        }
    }
}
