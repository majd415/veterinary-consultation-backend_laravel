<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = ChatRoom::with(['customer', 'vet', 'chatRequest']);

        if ($user->role === 'vet') {
            $query->where('vet_id', $user->id);
        } else {
            $query->where('customer_id', $user->id);
        }

        $rooms = $query->latest()->paginate(5);

        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vet_id' => 'required|exists:users,id',
            'transaction_id' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string',
        ]);

        $user = $request->user();
        $vetId = $request->vet_id;

        // Check if room exists
        $room = ChatRoom::where('customer_id', $user->id)
            ->where('vet_id', $vetId)
            ->first();

        if (!$room) {
            $room = ChatRoom::create([
                'customer_id' => $user->id,
                'vet_id' => $vetId,
            ]);
        } else {
            // CRITICAL: Refresh the session timestamp when reopening/paying
            $room->created_at = now();
            $room->save();
        }

        // Log Payment if present
        if ($request->transaction_id) {
            \App\Models\InfoPayment::create([
                'user_id' => $user->id,
                'amount' => $request->amount ?? 0,
                'currency' => $request->currency ?? 'USD',
                'payment_method' => 'stripe',
                'transaction_id' => $request->transaction_id,
                'status' => 'paid',
                'type' => 'chat_payment',
                'description' => 'Payment for Chat Room #' . $room->id,
                'payload' => json_encode($request->all()),
            ]);
        }

        return response()->json($room);
    }
}
