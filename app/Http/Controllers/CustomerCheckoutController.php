<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CourierRate;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class CustomerCheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cartIds = array_map('intval', explode(',', $request->carts));

        $carts = Cart::with('product')
            ->whereIn('id', $cartIds)
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->count() !== count($cartIds)) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Cart tidak valid');
        }

        $addresses = Address::where('user_id', auth()->id())->get();

        return view('customer.checkout.index', compact('carts', 'addresses'));
    }

    public function store(Request $request)
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

                // 💰 hitung subtotal
                $subtotal = $carts->sum(fn($c) => $c->product->final_price * $c->pcs);

                // 🚚 ongkir dari request
                $shipping = $request->shipping_cost;

                $total = $subtotal + $shipping;

                $date = date('dmY');       // tgl sekarang
                $time = date('His');        // jam-menit-detik sekarang
                $randomCode = rand(100, 999); // angka random
                $invoice = "ZK/{$date}/{$time}/{$randomCode}";
                // 🔥 SIMPAN TRANSACTION
                $transaction = Transaction::create([
                    'invoice' => $invoice, // simpan invoice
                    'user_id' => auth()->id(),
                    'address_id' => $request->address_id,

                    'subtotal' => $subtotal,
                    'shipping_cost' => $shipping,
                    'total' => $total,
                    'payment_method' => $request->payment_method, // 🔥 baru
                    'courier_name' => $request->courier_name,
                    'courier_service' => $request->courier_service,
                    'estimated_delivery' => $request->estimated_delivery,

                    'status' => 'pending',
                    'transaction_type' => 'Transaction',
                ]);

                // 🔥 SIMPAN DETAIL & KURANGI STOCK
                foreach ($carts as $cart) {

                    $product = $cart->product;

                    if ($product->stock < $cart->pcs) {
                        throw new \Exception("Stock tidak cukup untuk {$product->name}");
                    }

                    // kurangi stock produk
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
                'redirect' => route('customer.home.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
