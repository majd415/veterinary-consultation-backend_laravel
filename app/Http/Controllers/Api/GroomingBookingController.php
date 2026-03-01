<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroomingBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroomingBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = GroomingBooking::where('user_id', $request->user()->id)
            ->orderBy('pickup_date', 'desc')
            ->get();
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string',
            'num_animals' => 'required|integer|min:1',
            'animal_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking = GroomingBooking::create([
            'user_id' => $request->user()->id,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'num_animals' => $request->num_animals,
            'animal_type' => ['en' => $request->animal_type, 'ar' => $request->animal_type], // Placeholder for AR
            'status' => 'pending',
            'payment_status' => 'paid', // Assuming payment successful
        ]);

        return response()->json([
            'message' => 'Booking created successfully.',
            'booking' => $booking
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = GroomingBooking::findOrFail($id);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if date is in the past (allow same day)
        if (strtotime($booking->pickup_date) < strtotime(date('Y-m-d'))) {
            return response()->json(['message' => 'Cannot edit past appointments'], 422);
        }

        $validator = Validator::make($request->all(), [
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable',
            'delivery_date' => 'nullable|date',
            'delivery_time' => 'nullable',
            'client_name' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string',
            'num_animals' => 'nullable|integer|min:1',
            'animal_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'pickup_date', 'pickup_time', 'delivery_date', 'delivery_time',
            'client_name', 'client_phone', 'num_animals'
        ]);

        if ($request->has('animal_type')) {
            $data['animal_type'] = ['en' => $request->animal_type, 'ar' => $request->animal_type];
        }

        $booking->update($data);

        return response()->json([
            'message' => 'Booking updated successfully.',
            'booking' => $booking
        ]);
    }
}
