<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelBooking;

class AdminHotelBookingController extends Controller
{
    public function index()
    {
        $bookings = HotelBooking::with('user')->latest()->paginate(10);
        return view('admin.hotel_bookings.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $booking = HotelBooking::findOrFail($id);
        $booking->update($request->only(['status', 'payment_status']));
        return redirect()->back()->with('success', 'Booking updated successfully');
    }

    public function destroy($id)
    {
        HotelBooking::destroy($id);
        return redirect()->back()->with('success', 'Booking deleted successfully');
    }
}
