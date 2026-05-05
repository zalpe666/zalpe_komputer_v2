<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerKycController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'ktp_number' => 'required'
        ]);

        $user = auth()->user();

        $user->update([
            'ktp_number' => $request->ktp_number,
            'ktp_verified_at' => now(),
        ]);

        return back()->with('success', 'KYC berhasil (dummy)');
    }
}
