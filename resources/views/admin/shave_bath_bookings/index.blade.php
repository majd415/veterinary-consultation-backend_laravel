@extends('admin.layout')

@section('title', 'Shave & Bath Bookings')

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Info</th>
                     <th>Timings</th>
                    <th>Animal Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>
                        <!-- Info for person that make a booking -->
                        <div style="font-weight: bold;">{{ $booking->user->name ?? 'Unknown User' }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">User: {{ $booking->user->email ?? '' }}</div>
                        <div style="margin-top: 4px;">Client: {{ $booking->client_name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Phone: {{ $booking->client_phone }}</div>
                    </td>
                    <td>
                        <div>Pickup: {{ $booking->pickup_date }} {{ $booking->pickup_time }}</div>
                        <div>Delivery: {{ $booking->delivery_date }} {{ $booking->delivery_time }}</div>
                    </td>
                     <td>
                        <div>{{ $booking->num_animals }} Animal(s)</div>
                        <div>Type: {{ $booking->animal_type }}</div>
                    </td>
                    <td>
                        <form action="{{ route('admin.shave_bath_bookings.update', $booking->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.3); color: white; border: 1px solid var(--border); border-radius: 4px; padding: 2px;">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="fixed booking" {{ $booking->status == 'fixed booking' ? 'selected' : '' }}>Fixed Booking</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td>
                         <form action="{{ route('admin.shave_bath_bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Delete booking?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <div style="margin-top: 1rem;">{{ $bookings->links() }}</div>
    </div>
@endsection
