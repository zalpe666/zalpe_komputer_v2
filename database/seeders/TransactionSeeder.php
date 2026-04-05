<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::with('addresses')
            ->where('role', 'customer')
            ->get()
            ->filter(fn($user) => $user->addresses->isNotEmpty());

        $products = Product::all();

        // ========================
        // 10 transaksi hari ini
        // ========================
        for ($i = 0; $i < 10; $i++) {
            $this->createTransaction($users, $products, Carbon::now());
        }

        // ========================
        // 30 transaksi acak max 7 hari ke belakang
        // ========================
        for ($i = 0; $i < 30; $i++) {
            $randomDate = Carbon::now()->subDays(rand(0, 7))->setTime(rand(0, 23), rand(0, 59), rand(0, 59));
            $this->createTransaction($users, $products, $randomDate);
        }
    }

    private function createTransaction($users, $products, Carbon $date)
    {
        $user = $users->random();

        $dateStr = $date->format('dmY');
        $timeStr = $date->format('His');
        $randomCode = rand(100, 999);
        $invoice = "ZK/{$dateStr}/{$timeStr}/{$randomCode}";

        $selectedProducts = $products->random(rand(2, 3));
        $address = $user->addresses->random();

        // Hitung subtotal
        $subtotal = $selectedProducts->sum(function ($product) {
            $qty = rand(1, 3);
            return $product->final_price * $qty;
        });

        $shipping_cost = rand(10000, 50000);
        $total = $subtotal + $shipping_cost;

        // Pilih payment method random
        $paymentMethods = ['VA', 'QRIS', 'E-Wallet'];
        $payment_method = $paymentMethods[array_rand($paymentMethods)];

        // Pilih transaction_type random
        $transactionTypes = ['Shopping', 'Games', 'Top-Up', 'Phone Credit'];
        $transaction_type = $transactionTypes[array_rand($transactionTypes)];

        // Pilih transaction_status random
        $transactionStatuses = ['Pending', 'Waiting Payment', 'Packing', 'Sending', 'Delivered', 'Completed', 'Cancelled'];
        $transaction_status = $transactionStatuses[array_rand($transactionStatuses)];

        $transaction = Transaction::create([
            'invoice' => $invoice,
            'user_id' => 1,
            'address_id' => $address->id,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping_cost,
            'total' => $total,
            'courier_name' => 'JNE',
            'courier_service' => 'Reguler',
            'estimated_delivery' => '2-3 Hari',
            'payment_method' => $payment_method,
            'payment_status' => "Paid",
            'transaction_status' => $transaction_status,
            'notes' => null,
            'snap_token' => null,
            'transaction_type' => $transaction_type,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        foreach ($selectedProducts as $product) {
            $qty = rand(1, 3);
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'price' => $product->final_price,
                'qty' => $qty,
                'total' => $product->final_price * $qty,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}