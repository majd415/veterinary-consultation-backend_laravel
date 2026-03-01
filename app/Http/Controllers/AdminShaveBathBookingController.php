<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroomingBooking; // Maps to shave_bath_booking table

class AdminShaveBathBookingController extends Controller
{
    public function index()
    {
        // Eager load user to show "info for a person that make a booking"
        $bookings = GroomingBooking::with('user')->latest()->paginate(10);
        return view('admin.shave_bath_bookings.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $booking = GroomingBooking::findOrFail($id);
        
        // Status: pending, fixed booking, etc.
        $request->validate([
            'status' => 'required|string'
        ]);

        $booking->update($request->only(['status']));

        return redirect()->back()->with('success', 'Booking status updated successfully');
    }

    public function destroy($id)
    {
        GroomingBooking::destroy($id);
        return redirect()->back()->with('success', 'Booking deleted successfully');
    }
}
