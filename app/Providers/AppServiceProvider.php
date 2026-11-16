<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Cart; // Pastikan model Cart di-import

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Composer khusus navbar agar query tidak jalan berulang di setiap partial view
        View::composer('components.navbar', function ($view) {
            $brands = Brand::whereHas('products', function ($q) {
                $q->where('is_active', true);
            })->latest()->get();

            $categories = Category::whereHas('products', function ($q) {
                $q->where('is_active', true);
            })->latest()->get();

            // Default kosong untuk guest
            $notifications = collect();
            $unreadCount = 0;
            $carts = collect();
            $cartCount = 0;

            if (auth()->check()) {
                $userId = auth()->id();

                $notifications = Notification::where('user_id', $userId)
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadCount = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();

                // Ambil data keranjang beserta relasi produknya
                $carts = Cart::with('product')
                    ->where('user_id', $userId)
                    ->latest()
                    ->get();
                // Total jumlah item (atau pakai $carts->count() jika ingin hitung jenis produknya)
                $cartCount = $carts->sum('pcs');
            }

            $view->with([
                'brands'        => $brands,
                'categories'    => $categories,
                'notifications' => $notifications,
                'unreadCount'   => $unreadCount,
                'carts'         => $carts,
                'cartCount'     => $cartCount,
            ]);
        });
    }
}
