<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    // Fetch messages for a specific chat room
    public function getMessages(Request $request, $roomId)
    {
        // Ensure user belongs to this chat room
        $chatRoom = ChatRoom::findOrFail($roomId);
        if ($request->user()->id !== $chatRoom->customer_id && $request->user()->id !== $chatRoom->vet_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('chat_room_id', $roomId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    // Send a message
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 2MB max
            'type' => 'required|in:text,image',
            'image_url' => 'nullable|string' // In case they use Firebase Storage URL
        ]);

        $chatRoom = ChatRoom::findOrFail($roomId);
        if ($request->user()->id !== $chatRoom->customer_id && $request->user()->id !== $chatRoom->vet_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // 60-minute locking logic
        if ($chatRoom->created_at->diffInMinutes(now()) >= 60) {
            return response()->json([
                'error' => 'Session Expired',
                'message' => 'This consultation session has ended. Please pay to continue.',
                'is_locked' => true
            ], 403);
        }

        $imageUrl = $request->image_url;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('chat_images', $filename, 'public');
            
            // Store only relative path
            $imageUrl = 'storage/' . $path;
        }

        $message = \App\Models\Message::create([
            'chat_room_id' => $roomId,
            'user_id' => $request->user()->id,
            'message' => $request->message,
            'type' => $request->type,
            'image_url' => $imageUrl,
        ]);

        // Load user relations for broadcasting
        $message->load('user');

        // Broadcast the event
        broadcast(new MessageSent($message));

        // Save Notification for Persistence
        try {
            $recipientId = ($request->user()->id == $chatRoom->customer_id) 
                ? $chatRoom->vet_id 
                : $chatRoom->customer_id;
            
            $recipient = \App\Models\User::find($recipientId);
            $lang = $recipient->language ?? 'en';

            $title = $lang == 'ar' 
                ? 'رسالة جديدة من ' . $request->user()->name
                : 'New Message from ' . $request->user()->name;
            
            $body = $request->type == 'image' 
                ? ($lang == 'ar' ? 'أرسل لك صورة' : 'Sent you an image')
                : ($request->message ?? '');

            \App\Models\Notification::create([
                'user_id' => $recipientId,
                'title' => $title,
                'body' => $body,
                'data' => [
                    'type' => 'chat',
                    'room_id' => $roomId,
                    'sender_name' => $request->user()->name,
                ],
            ]);

            // Send FCM Notification
            if ($recipient->fcm_token) {
                \App\Services\FirebaseService::sendNotification(
                    $recipient->fcm_token,
                    $title,
                    $body,
                    [
                        'type' => 'chat',
                        'room_id' => $roomId,
                        'sender_name' => $request->user()->name,
                        'sender_id' => $request->user()->id,
                        'created_at' => $chatRoom->created_at->toIso8601String(),
                        'sender_image' => $request->user()->avatar ?? '',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        // Include IDs to help frontend construct arguments
                        'vet_id' => $chatRoom->vet_id,
                        'customer_id' => $chatRoom->customer_id,
                    ]
                );
            }
        } catch (\Exception $e) {
            \Log::error('Notification Save Failed: ' . $e->getMessage());
        }

        return response()->json(['status' => 'Message Sent!', 'message' => $message]);
    }
}
