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
use App\Models\UserGame;

class CustomerCheckoutNowController extends Controller
{
    public function index($id)
    {
        $product = Product::findOrFail($id);

        $wallet = Wallet::where('user_id', auth()->id())
            ->where('status', 'verified')
            ->first();

        $walletAvailable = $wallet && $wallet->balance >= $product->price;

        return view('customer.checkout.now.index', compact(
            'product',
            'wallet',
            'walletAvailable'
        ));
    }
    public function store(Request $request)
    {
        // ==========================================
        // 1. VALIDASI REQUEST
        // ==========================================
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'bank' => 'required|in:bca,mandiri,bni,wallet',
        ]);

        // ==========================================
        // 2. AMBIL DATA PRODUK DAN USER
        // ==========================================
        $product = Product::findOrFail($request->product_id);
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($product->is_out_of_stock) {
            return back()->with('error', 'Product out of stock');
        }
        if ($request->bank === 'wallet') {

            $wallet = Wallet::where('user_id', $user->id)
                ->where('status', 'verified')
                ->first();

            if (!$wallet) {
                return back()->with('error', 'Wallet belum tersedia atau belum terverifikasi.');
            }

            if ($wallet->balance < $product->final_price) {
                return back()->with('error', 'Saldo Wallet tidak mencukupi.');
            }
        }
        if ($product->category_id == 18) {

            $alreadyOwned = UserGame::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();

            if ($alreadyOwned) {
                return back()->with(
                    'error',
                    'Kamu sudah memiliki game ini.'
                );
            }
        }
        // ==========================================
        // 3. HITUNG TOTAL PEMBAYARAN
        // ==========================================
        $paymentMethod = $request->bank === 'wallet'
            ? 'wallet'
            : 'midtrans';

        $totalDiscount = $product->default_price - $product->final_price;
        $shippingCost = 0;
        $total = $product->final_price + $shippingCost;

        // ==========================================
        // 4. GENERATE INVOICE
        // ==========================================
        $invoice = 'ZK-' . date('dmY') . '-' . date('His') . '-' . rand(100, 999);

        try {
            // ==========================================
            // 5. DATABASE TRANSACTION
            // ==========================================
            DB::transaction(function () use (
                $request,
                $product,
                $user,
                $paymentMethod,
                $totalDiscount,
                $shippingCost,
                $total,
                $invoice
            ) {
                $transaction = Transaction::create([
                    'invoice' => $invoice,
                    'transaction_type' => match ($product->category->name) {
                        'Games' => 'Games',
                        'Digital' => 'Digital',
                        'Phone Credit' => 'Phone Credit',
                        default => 'Shopping',
                    },
                    'user_id' => $user->id,
                    'subtotal' => $product->default_price,
                    'discount_by_merchant' => $totalDiscount,
                    'shipping_cost' => $shippingCost,
                    'transaction_status' => 'Waiting Payment',
                    'total' => $total,
                    'payment_method' => $paymentMethod,
                    'bank' => $request->bank === 'wallet'
                        ? null
                        : $request->bank,
                    'payment_status' => 'Unpaid',
                ]);

                // --------------------------------------
                // SIMPAN DETAIL PRODUK
                // --------------------------------------
                $transaction->transactionDetails()->create([
                    'product_id' => $product->id,
                    'qty' => 1,
                    'price' => $product->final_price,
                    'total' => $product->final_price,
                ]);

                // ======================================
                // 6. PEMBAYARAN MIDTRANS
                // ======================================
                if ($paymentMethod === 'midtrans') {

                    Config::$serverKey = config('midtrans.serverKey');
                    Config::$isProduction = config('midtrans.isProduction');

                    // Validasi konfigurasi Midtrans
                    if (empty(Config::$serverKey)) {
                        throw new \Exception('Midtrans Server Key belum dikonfigurasi.');
                    }

                    // ----------------------------------
                    // PARAMETER MIDTRANS
                    // ----------------------------------
                    if ($request->bank === 'mandiri') {

                        $params = [
                            'transaction_details' => [
                                'order_id' => $invoice,
                                'gross_amount' => (int) $total,
                            ],
                            'payment_type' => 'echannel',
                            'echannel' => [
                                'bill_info1' => 'Payment:',
                                'bill_info2' => 'Online purchase',
                            ],
                        ];
                    } else {

                        $params = [
                            'transaction_details' => [
                                'order_id' => $invoice,
                                'gross_amount' => (int) $total,
                            ],
                            'payment_type' => 'bank_transfer',
                            'bank_transfer' => [
                                'bank' => $request->bank,
                            ],
                        ];
                    }

                    Log::info('CHECKOUT NOW: Mengirim request Midtrans', [
                        'invoice' => $invoice,
                        'bank' => $request->bank,
                        'params' => $params,
                        'production' => Config::$isProduction,
                    ]);

                    // ----------------------------------
                    // REQUEST MIDTRANS
                    // ----------------------------------
                    $response = CoreApi::charge($params);

                    Log::info('CHECKOUT NOW: Response Midtrans', [
                        'response' => json_decode(
                            json_encode($response),
                            true
                        ),
                    ]);

                    // ----------------------------------
                    // AMBIL DATA PEMBAYARAN
                    // ----------------------------------
                    $vaNumber = null;
                    $billerCode = null;
                    $billKey = null;
                    $expiry = $response->expiry_time ?? null;

                    if (!empty($response->va_numbers)) {
                        $vaNumber = $response->va_numbers[0]->va_number ?? null;
                    }

                    if (!empty($response->bill_key)) {
                        $billKey = $response->bill_key;
                        $billerCode = $response->biller_code ?? null;
                    }

                    // ----------------------------------
                    // UPDATE TRANSAKSI
                    // ----------------------------------
                    $transaction->update([
                        'va_number' => $vaNumber,
                        'biller_code' => $billerCode,
                        'bill_key' => $billKey,
                        'expired_at' => $expiry,
                        'bank' => $request->bank,
                        'payment_status' => 'Unpaid',
                    ]);
                }

                // ======================================
                // 7. PEMBAYARAN WALLET
                // ======================================
                elseif ($paymentMethod === 'wallet') {
                }
            });

            // ==========================================
            // 8. CHECKOUT BERHASIL
            // ==========================================
            Log::info('CHECKOUT NOW: Berhasil', [
                'invoice' => $invoice,
            ]);
            if ($paymentMethod === 'wallet') {

                $transaction = Transaction::where('invoice', $invoice)->firstOrFail();

                return redirect()
                    ->route('customer.wallet.pay.index', $transaction->id);
            }
            return redirect()
                ->route('customer.transaction.index')
                ->with('success', 'Checkout successful');
        } catch (\Throwable $e) {

            // ==========================================
            // 9. PENANGANAN ERROR
            // ==========================================
            Log::error('CHECKOUT NOW ERROR', [
                'invoice' => $invoice,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with(
                'error',
                'Checkout gagal: ' . $e->getMessage()
            );
        }
    }
}
