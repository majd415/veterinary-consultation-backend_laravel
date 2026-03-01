@extends('admin.layout')

@section('title', 'Hotel Bookings')

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User / Info</th>
                    <th>Dates</th>
                    <th>Pet Info</th>
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
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $booking->user->email ?? '' }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Name: {{ $booking->owner_name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">Phone: {{ $booking->owner_phone }}</div>
                    </td>
                    <td>
                        <div>In: {{ $booking->check_in_date }}</div>
                        <div>Out: {{ $booking->check_out_date }}</div>
                        <div style="font-size: 0.8rem; color: #10b981;">Total: ${{ $booking->total_cost }}</div>
                    </td>
                    <td>
                        <div>{{ $booking->num_pets }} Pet(s)</div>
                        <div>Type: {{ $booking->pet_type }}</div>
                    </td>
                    <td>
                        <form action="{{ route('admin.hotel_bookings.update', $booking->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.3); color: white; border: 1px solid var(--border); border-radius: 4px; padding: 2px; margin-bottom: 4px;">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td>
                         <form action="{{ route('admin.hotel_bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Delete booking?');">
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
