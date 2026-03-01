<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRequest;
use App\Models\ChatRoom;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ChatRequestController extends Controller
{
    /**
     * Store a new chat request and notify all vets.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Create the request
        $chatRequest = ChatRequest::create([
            'customer_id' => $user->id,
            'status' => 'waiting',
        ]);

        // Persistent notification for history (Optional: could be separate)
        // For now, we only notify Vets via FCM

        // Notify all vents
        $vets = User::where('role', 'vet')->whereNotNull('fcm_token')->get();
        $tokens = $vets->pluck('fcm_token')->toArray();

        if (!empty($tokens)) {
            FirebaseService::sendBatchNotification(
                $tokens,
                "New Vet Request",
                "A client needs veterinary help now",
                ['chat_request_id' => $chatRequest->id]
            );
        }

        return response()->json([
            'message' => 'Request sent to all available vets.',
            'chat_request' => $chatRequest
        ]);
    }

    /**
     * Atomic transaction for a vet to accept a request.
     */
    public function accept(Request $request, $id)
    {
        $vet = Auth::user();

        if ($vet->role !== 'vet') {
            return response()->json(['error' => 'Only vets can accept requests.'], 403);
        }

        return DB::transaction(function () use ($id, $vet) {
            $chatRequest = ChatRequest::where('id', $id)->lockForUpdate()->first();

            if (!$chatRequest) {
                return response()->json(['error' => 'Request not found.'], 404);
            }

            if ($chatRequest->status !== 'waiting') {
                return response()->json(['error' => 'already_taken'], 400);
            }

            // Accept the request
            $chatRequest->update([
                'status' => 'taken',
                'taken_by' => $vet->id,
            ]);

            // Create the chat room
            $chatRoom = ChatRoom::create([
                'customer_id' => $chatRequest->customer_id,
                'vet_id' => $vet->id,
                'chat_request_id' => $chatRequest->id,
            ]);

            // Notify the customer that a vet has accepted
            $customer = User::find($chatRequest->customer_id);
            if ($customer && $customer->fcm_token) {
                FirebaseService::sendNotification(
                    $customer->fcm_token,
                    "Vet Accepted",
                    "A vet has accepted your request. Starting chat...",
                    [
                        'chat_room_id' => $chatRoom->id,
                        'vet_name' => $vet->name
                    ]
                );
            }

            return response()->json([
                'message' => 'Request accepted successfully.',
                'chat_room' => $chatRoom
            ]);
        });
    }

    /**
     * List all waiting requests (for vets).
     */
    public function index()
    {
        $requests = ChatRequest::with('customer')
            ->where('status', 'waiting')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->get();

        return response()->json($requests);
    }
}
