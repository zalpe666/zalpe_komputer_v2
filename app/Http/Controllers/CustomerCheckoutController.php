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
        $carts = Cart::with('product')->whereIn('id', $cartIds)->where('user_id', auth()->id())->get();
        if ($carts->count() !== count($cartIds)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart tidak valid');
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
}
