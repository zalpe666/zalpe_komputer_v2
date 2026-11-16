<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function create(
        $userId,
        $title,
        $message,
        $type = 'Shopping',
        $link = null
    ) {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }
}