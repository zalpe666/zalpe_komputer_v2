<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;


// Route::post('/midtrans/callback', function () {
//     \Log::info('KENA CALLBACK');
//     return response()->json(['ok' => true]);
// });
Route::post('/midtrans/callback', [MidtransController::class, 'callback']);