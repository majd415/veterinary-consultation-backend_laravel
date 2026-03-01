<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        $users = User::whereNotNull('fcm_token')->get(['id', 'name', 'fcm_token']);
        return view('admin.notifications.index', compact('notifications', 'users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'title_ar' => 'nullable',
            'body' => 'required',
            'body_ar' => 'nullable',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $title = $request->title;
        $title_ar = $request->title_ar ?? $title;
        $body = $request->body;
        $body_ar = $request->body_ar ?? $body;
        
        $firebase = new FirebaseService();

        if ($request->user_id) {
            $user = User::find($request->user_id);
            if ($user->fcm_token) {
                // Send in user's preferred language if we had it, but for now we send what's in the box
                // Usually Firebase notification shows what's in 'title'/'body' 
                // We'll send the primary (EN) for the push, but store both for the in-app inbox
                $firebase->sendNotification($user->fcm_token, $title, $body, ['type' => 'manual']);
                Notification::create([
                    'user_id' => $user->id,
                    'title' => ['en' => $title, 'ar' => $title_ar],
                    'body' => ['en' => $body, 'ar' => $body_ar],
                    'type' => 'manual'
                ]);
            }
        } else {
            // Broadcast
            $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
            if (!empty($tokens)) {
                $firebase->sendBatchNotification($tokens, $title, $body, ['type' => 'broadcast']);
                $users = User::whereNotNull('fcm_token')->get();
                foreach($users as $u) {
                    Notification::create([
                        'user_id' => $u->id,
                        'title' => ['en' => $title, 'ar' => $title_ar],
                        'body' => ['en' => $body, 'ar' => $body_ar],
                        'type' => 'broadcast'
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Notifications sent successfully');
    }

    public function destroy($id)
    {
        Notification::destroy($id);
        return redirect()->back()->with('success', 'Notification deleted');
    }
}
