<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\GroomingBookingController;
use App\Http\Controllers\Api\HotelBookingController;

use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TopRatedItemController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ChatRequestController;
use App\Http\Controllers\Api\ChatRoomController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ChatController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/update-profile', [AuthController::class, 'updateProfile']);
    
    // Grooming
    Route::get('/grooming-bookings', [GroomingBookingController::class, 'index']);
    Route::post('/grooming-bookings', [GroomingBookingController::class, 'store']);
    Route::put('/grooming-bookings/{id}', [GroomingBookingController::class, 'update']);
    
    // Hotel
    Route::get('/hotel-bookings', [HotelBookingController::class, 'index']);
    Route::post('/hotel-bookings', [HotelBookingController::class, 'store']);
    Route::put('/hotel-bookings/{id}', [HotelBookingController::class, 'update']);

    // Store Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Chat On-Demand
    Route::get('/chat-requests', [ChatRequestController::class, 'index']);
    Route::post('/chat-requests', [ChatRequestController::class, 'store']);
    Route::post('/chat-requests/{id}/accept', [ChatRequestController::class, 'accept']);

    Route::get('/chat-rooms', [ChatRoomController::class, 'index']);
    Route::post('/chat-rooms', [ChatRoomController::class, 'store']);
    Route::get('/vets', [AuthController::class, 'getVets']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
     Route::post('/user/fcm-token', [AuthController::class, 'updateFcmToken']);

   // Route::post('/user/fcm-token', [NotificationController::class, 'updateFcmToken']);

    // Chat Messages (Socket Implementation)
    Route::get('/chat-rooms/{id}/messages', [ChatController::class, 'getMessages']);
    Route::post('/chat-rooms/{id}/messages', [ChatController::class, 'sendMessage']);

    // Payments - Intents remain protected
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);

    // Broadcasting Authorization
    Route::post('/broadcasting/auth', function (Request $request) {
        $user = $request->user();
        \Log::info('📡 Broadcasting Auth Attempt', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'channel' => $request->channel_name,
            'socket_id' => $request->socket_id,
            'auth_token' => $request->header('Authorization') ? 'Present' : 'Missing'
        ]);
        return Broadcast::auth($request);
    });

    Broadcast::routes();
});

Route::get('/test-broadcast/{roomId}', function($roomId) {
    $message = \App\Models\Message::where('chat_room_id', $roomId)->latest()->first();
    if ($message) {
        \Log::info('🧪 Manually Triggering Broadcast', [
            'room' => $roomId, 
            'msg_id' => $message->id,
            'sender_id' => $message->user_id
        ]);
        broadcast(new \App\Events\MessageSent($message));
        return response()->json(['status' => 'Broadcasted!', 'message_id' => $message->id]);
    }
    return response()->json(['error' => "No message found for room $roomId"], 404);
});

Route::get('/list-rooms', function() {
    return response()->json(
        \App\Models\ChatRoom::withCount('messages')->get()
    );
});

// Debug route (Temporary)
Route::get('/payment/debug', function() {
    return response()->json(\App\Models\PaymentSetting::all());
});

// Public Payment Routes
Route::get('/payment/prices', [PaymentController::class, 'getPrices']);
Route::get('/payment/stripe-key', [PaymentController::class, 'getStripeKey']);


Route::prefix('auth')->group(function () {
    Route::post('/send-code', [AuthController::class, 'sendCode']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/upload-photo', [AuthController::class, 'uploadProfilePhoto']);
});

Route::get('/slider-offers', [SliderController::class, 'getOffers']);

// Store Public Routes
Route::get('/product-categories', [ProductCategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);

// Top Rated & Settings
Route::get('/top-rated-items', [TopRatedItemController::class, 'index']);
Route::get('/settings', [SettingController::class, 'index']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is reachable!']);
});
