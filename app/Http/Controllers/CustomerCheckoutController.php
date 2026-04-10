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

class CustomerCheckoutController extends Controller
{
    public function Oldindex(Request $request)
    {
        $cartIds = array_map('intval', explode(',', $request->carts));
        $carts = Cart::with('product')->whereIn('id', $cartIds)->where('user_id', auth()->id())->get();
        if ($carts->count() !== count($cartIds)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart tidak valid');
        }
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('customer.checkout.index', compact('carts', 'addresses'));
    }

    public function Oldstore(Request $request)
    {
        $cartIds = explode(',', $request->carts);

        $carts = Cart::with('product')
            ->whereIn('id', $cartIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return back()->with('error', 'Cart kosong');
        }

        try {
            DB::transaction(function () use ($carts, $request, $cartIds, &$transaction) {

                // 💰 hitung subtotal dan total diskon merchant
                $subtotal = 0;
                $total_discount = 0;

                foreach ($carts as $cart) {
                    $product = $cart->product;

                    $subtotal += $product->default_price * $cart->pcs;
                    $total_discount += ($product->default_price - $product->final_price) * $cart->pcs;
                }

                // 🚚 ongkir dari request
                $shipping = $request->shipping_cost;
                $total = $subtotal + $shipping - $total_discount;

                $date = date('dmY');
                $time = date('His');
                $randomCode = rand(100, 999);
                $invoice = "ZK/{$date}/{$time}/{$randomCode}";

                // 🔥 SIMPAN TRANSACTION
                $transaction = Transaction::create([
                    'invoice' => $invoice,
                    'user_id' => auth()->id(),
                    'address_id' => $request->address_id,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shipping,
                    'total' => $total,
                    'discount_by_merchant' => $total_discount, // <--- simpan total diskon di sini
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'Unpaid',
                    'transaction_status' => 'Waiting Payment',
                    // 'payment_status' => 'Paid',
                    // 'transaction_status' => 'Packing',
                    'courier_name' => $request->courier_name,
                    'courier_service' => $request->courier_service,
                    'estimated_delivery' => $request->estimated_delivery,
                    'status' => 'pending',
                    'transaction_type' => 'Shopping',
                    'total_weight' => $request->total_weight,
                ]);

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

            return response()->json([
                'success' => true,
                'redirect' => route('customer.transaction.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index(Request $request)
    {
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

        return view('customer.checkout.index', compact('carts', 'addresses', 'totalWeight'));
    }

    public function store(Request $request)
    {
        // Validasi form dasar
        $request->validate([
            'carts' => 'required',
            'address_id' => 'required',
            'courier' => 'required',
            'payment_method' => 'required'
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
                $invoice = "ZK/{$date}/{$time}/{$randomCode}";

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
                    'payment_status' => 'Unpaid',
                    'transaction_status' => 'Waiting Payment',
                    'courier_name' => $courier['name'] ?? '',
                    'courier_service' => $courier['service'] ?? '',
                    'estimated_delivery' => $courier['estimated_delivery_time'] ?? '',
                    'status' => 'pending',
                    'transaction_type' => 'Shopping',
                    'total_weight' => $total_weight,
                ]);

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
    public function checkoutNowIndex($id)
    {
        $product = Product::findOrFail($id);
        return view('customer.checkout.now.index', compact('product'));
    }
    public function checkoutNowProcess(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $user = auth()->user();

        // Validasi
        if ($product->is_out_of_stock) {
            return back()->with('error', 'Product out of stock');
        }
        $total_discount = $product->default_price - $product->final_price;
        $date = date('dmY');
        $time = date('His');
        $randomCode = rand(100, 999);
        $invoice = "ZK/{$date}/{$time}/{$randomCode}";
        $shipping_cost = 0;
        // Buat transaksi
        $transaction = Transaction::create([
            'invoice' => $invoice,
            'transaction_type' => 'Top-Up',
            'user_id' => $user->id,
            'subtotal' => $product->default_price,
            'discount_by_merchant' => $total_discount,
            'shipping_cost' => $shipping_cost,
            'transaction_status' => 'Waiting Payment',
            'total' => $product->final_price + $shipping_cost,
            'payment_method' => $request->payment_method,
        ]);

        // Simpan detail
        $transaction->transactionDetails()->create([
            'product_id' => $product->id,
            'qty' => 1,
            'price' => $product->final_price,
            'total' => $product->final_price,
        ]);


        return redirect()->route('customer.transaction.index')->with('success', 'Checkout successful');
    }
}
