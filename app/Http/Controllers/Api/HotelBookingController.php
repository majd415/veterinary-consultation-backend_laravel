<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = HotelBooking::where('user_id', $request->user()->id)
            ->orderBy('check_in_date', 'desc')
            ->get();
        return response()->json($bookings);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in_date' => 'required|date',
            'check_in_time' => 'required',
            'check_out_date' => 'required|date',
            'check_out_time' => 'required',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string',
            'num_pets' => 'required|integer|min:1',
            'pet_type' => 'required|string',
            'total_days' => 'required|integer|min:1',
            'total_cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $booking = HotelBooking::create([
            'user_id' => $request->user()->id,
            'check_in_date' => $request->check_in_date,
            'check_in_time' => $request->check_in_time,
            'check_out_date' => $request->check_out_date,
            'check_out_time' => $request->check_out_time,
            'owner_name' => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'num_pets' => $request->num_pets,
            'pet_type' => ['en' => $request->pet_type, 'ar' => $request->pet_type],
            'total_days' => $request->total_days,
            'total_cost' => $request->total_cost,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        return response()->json([
            'message' => 'Booking created successfully.',
            'booking' => $booking
        ]);
    }

    public function update(Request $request, $id)
    {
        $booking = HotelBooking::findOrFail($id);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if date is in the past (allow same day)
        if (strtotime($booking->check_in_date) < strtotime(date('Y-m-d'))) {
            return response()->json(['message' => 'Cannot edit past bookings'], 422);
        }

        $validator = Validator::make($request->all(), [
            'check_in_date' => 'nullable|date',
            'check_in_time' => 'nullable',
            'check_out_date' => 'nullable|date',
            'check_out_time' => 'nullable',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string',
            'num_pets' => 'nullable|integer|min:1',
            'pet_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time',
            'owner_name', 'owner_phone', 'num_pets', 'total_days', 'total_cost'
        ]);

        if ($request->has('pet_type')) {
            $data['pet_type'] = ['en' => $request->pet_type, 'ar' => $request->pet_type];
        }

        $booking->update($data);

        return response()->json([
            'message' => 'Booking updated successfully.',
            'booking' => $booking
        ]);
    }
}
