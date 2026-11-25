<?php

namespace App\Http\Controllers;

use App\Models\Notification;

use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    public function index()
    {
        return view('customer.notification.index');
    }
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
    public function read($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Update status notifikasi
        $notification->update([
            'is_read' => true,
        ]);

        // Redirect ke link notifikasi
        $link = $notification->link;

        if (!$link || !str_starts_with($link, '/')) {
            return redirect()->route('customer.home.index');
        }

        return redirect($link);
    }
}
