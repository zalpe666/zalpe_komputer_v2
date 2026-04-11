<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use App\Models\Brand;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $notifications = Notification::where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadCount = Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();

                $view->with([
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount
                ]);
            }
        });
        View::composer('components.navbar', function ($view) {

            $brands = Brand::whereHas('products', function ($q) {
                $q->where('is_active', true);
            })->latest()->get();

            $categories = Category::whereHas('products', function ($q) {
                $q->where('is_active', true);
            })->latest()->get();

            $view->with([
                'brands' => $brands,
                'categories' => $categories
            ]);
        });
    }
}
