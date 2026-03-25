<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardBrandController;
use App\Http\Controllers\DashboardCategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:master,admin'])->prefix('dashboard')->as('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/categories', [DashboardCategoriesController::class, 'index'])->name('categories.index');
    Route::get('/brand', [DashboardBrandController::class, 'index'])->name('brand.index');
    Route::get('/product', [DashboardProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [DashboardProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [DashboardProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [DashboardProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [DashboardProductController::class, 'update'])->name('product.update');
    Route::post('/product/delete/{id}', [DashboardProductController::class, 'destroy'])->name('product.delete');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    // TODO: admin routes
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    // TODO: cashier routes
});
Route::middleware(['auth', 'role:customer'])->prefix('home')->as('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('home.index');

});
require __DIR__ . '/auth.php';
