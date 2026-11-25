<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;

use App\Models\CourierRate;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\CoreApi;
use App\Models\Wallet;
use App\Services\NotificationService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Log;

class CustomerCheckoutController extends Controller
{
    public function index(Request $request)
    {
        $wallet = Wallet::where('user_id', auth()->id())->first();
        $walletBalance = $wallet?->balance ?? 0;
        $cartIds = array_map('intval', explode(',', $request->carts));
        $carts = Cart::with('product')->whereIn('id', $cartIds)->where('user_id', auth()->id())->get();

        if ($carts->count() !== count($cartIds) || $carts->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart tidak valid');
        }

        $addresses = Address::where('user_id', auth()->id())->get();

        // Hitung total berat di server untuk dilempar ke JS
        $totalWeight = $carts->sum(function ($c) {
            return ($c->product->weight ?? 1000) * $c->pcs;
        });
        $subtotal = $carts->sum(function ($c) {
            return $c->product->default_price * $c->pcs;
        });

        $discount_by_merchant = $carts->sum(function ($c) {
            return ($c->product->default_price - $c->product->final_price) * $c->pcs;
        });

        $subtotal_final = $subtotal - $discount_by_merchant;
        return view('customer.checkout.index', compact(
            'carts',
            'addresses',
            'totalWeight',
            'walletBalance',
            'subtotal',
            'discount_by_merchant',
            'subtotal_final'
        ));
    }

    public function store(Request $request)
    {
        // Validasi form dasar
        $request->validate([
            'carts' => 'required',
            'address_id' => 'required',
            'courier' => 'required',
            'bank' => 'required_if:payment_method,midtrans',
            'payment_method' => 'required',
        ]);

        $cartIds = explode(',', $request->carts);
        $carts = Cart::with('product')
            ->whereIn('id', $cartIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Cart kosong');
        }

        try {
            DB::transaction(function () use ($carts, $request, $cartIds) {

                // 💰 Hitung ulang SEMUA di server (jangan percaya data dari Client/JS)
                $subtotal = 0;
                $total_discount = 0;
                $total_weight = 0;

                foreach ($carts as $cart) {
                    $product = $cart->product;
                    $subtotal += $product->default_price * $cart->pcs;
                    $total_discount += ($product->default_price - $product->final_price) * $cart->pcs;
                    $total_weight += ($product->weight ?? 1000) * $cart->pcs;
                }

                $subtotal_after_discount = $subtotal - $total_discount;

                // Validasi COD dari server
                if ($request->payment_method === 'cod' && $subtotal_after_discount > 1000000) {
                    throw new \Exception("COD hanya bisa untuk subtotal maksimal Rp 1.000.000");
                }

                // 🚚 Decode data kurir yang dikirim sebagai JSON string dari input radio
                $courier = json_decode($request->courier, true);
                $price_per_kg = $courier['price_per_kg'] ?? 0;

                // Hitung Ongkir di server
                $shipping = ceil($total_weight / 1000) * $price_per_kg;
                $total = $subtotal_after_discount + $shipping;

                $date = date('dmY');
                $time = date('His');
                $randomCode = rand(100, 999);
                $invoice = "ZK-{$date}-{$time}-{$randomCode}";

                // 🔥 SIMPAN TRANSACTION
                $transaction = Transaction::create([
                    'invoice' => $invoice,
                    'user_id' => auth()->id(),
                    'address_id' => $request->address_id,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shipping,
                    'total' => $total,
                    'discount_by_merchant' => $total_discount,
                    'payment_method' => $request->payment_method,
                    'bank' => $request->bank,
                    // 'payment_status' => 'Paid',
                    // 'transaction_status' => 'Packing',
                    'payment_status' => 'Unpaid',
                    'transaction_status' => 'Waiting Payment',
                    'courier_name' => $courier['name'] ?? '',
                    'courier_service' => $courier['service'] ?? '',
                    'estimated_delivery' => $courier['estimated_delivery_time'] ?? '',
                    'status' => 'pending',
                    'transaction_type' => 'Shopping',
                    'total_weight' => $total_weight,

                ]);
                // 🔥 HIT MIDTRANS (HANYA JIKA BANK TRANSFER)
                // 🔥 HANDLE PAYMENT

                if ($request->payment_method === 'wallet') {
                    WalletService::deduct(
                        auth()->id(),
                        $total,
                        'Pembayaran order #' . $invoice
                    );
                    NotificationService::create(
                        auth()->id(),
                        'Payment Successful',
                        'Order #' . $invoice . ' has been successfully paid using Zalpe Pay',
                        'Shopping',
                        '/home/transaction/' . $transaction->id,
                    );
                    // ✅ langsung paid
                    $transaction->update([
                        'payment_status' => 'Paid',
                        'payment_date' => now(),
                        'transaction_status' => 'Packing',
                    ]);
                } elseif ($request->payment_method === 'midtrans') {

                    Config::$serverKey = config('midtrans.serverKey');
                    Config::$isProduction = config('midtrans.isProduction');

                    // 🔥 CEK BANK
                    if ($request->bank === 'mandiri') {

                        $params = [
                            'transaction_details' => [
                                'order_id' => $invoice,
                                'gross_amount' => (int)$total,
                            ],
                            'payment_type' => 'echannel',
                            'echannel' => [
                                'bill_info1' => 'Payment:',
                                'bill_info2' => 'Online purchase'
                            ]
                        ];
                    } else {

                        $params = [
                            'transaction_details' => [
                                'order_id' => $invoice,
                                'gross_amount' => (int)$total,
                            ],
                            'payment_type' => 'bank_transfer',
                            'bank_transfer' => [
                                'bank' => $request->bank
                            ]
                        ];
                    }

                    $response = CoreApi::charge($params);

                    $vaNumber = null;
                    $billerCode = null;
                    $billKey = null;
                    $expiry = $response->expiry_time ?? null;

                    if (!empty($response->va_numbers)) {
                        $vaNumber = $response->va_numbers[0]->va_number;
                    }

                    if (!empty($response->bill_key)) {
                        $billKey = $response->bill_key;
                        $billerCode = $response->biller_code;
                    }

                    $transaction->update([
                        'va_number'   => $vaNumber,
                        'biller_code' => $billerCode,
                        'bill_key'    => $billKey,
                        'expired_at'  => $expiry,
                        'bank'        => $request->bank,
                        'payment_status' => 'Pending',
                    ]);
                } elseif ($request->payment_method === 'cod') {

                    $transaction->update([
                        'payment_status' => 'COD',
                        'transaction_status' => 'Waiting Courier',
                    ]);
                }
                // 🔥 SIMPAN DETAIL & KURANGI STOCK
                foreach ($carts as $cart) {
                    $product = $cart->product;

                    if ($product->stock < $cart->pcs) {
                        throw new \Exception("Stock tidak cukup untuk {$product->name}");
                    }

                    $product->decrement('stock', $cart->pcs);

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'price' => $product->final_price,
                        'qty' => $cart->pcs,
                        'total' => $product->final_price * $cart->pcs,
                    ]);
                }

                // 🧹 hapus cart
                Cart::whereIn('id', $cartIds)->delete();
            });

            // Redirect murni via PHP
            return redirect()->route('customer.transaction.index')->with('success', 'Checkout Berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
