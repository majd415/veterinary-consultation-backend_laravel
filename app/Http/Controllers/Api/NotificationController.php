<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * List all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['message' => 'Notification marked as read.']);
    }

    /**
     * Update FCM token for the user.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        Auth::user()->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json(['message' => 'FCM token updated successfully.']);
    }

    /**
     * Get unread notification count for authenticated user
     */
    public function getUnreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
