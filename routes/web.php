<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:master,admin'])->prefix('dashboard')->as('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
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
require __DIR__.'/auth.php';
