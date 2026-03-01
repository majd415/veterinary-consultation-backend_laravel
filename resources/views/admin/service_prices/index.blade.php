@extends('admin.layout')

@section('title', 'Service Prices')

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Current Price</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prices as $price)
                <tr>
                    <td>{{ $price->service_key }}</td>
                    <td>
                        <form action="{{ route('admin.service_prices.update', $price->id) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                            @csrf @method('PUT')
                            <span style="color: var(--text-muted);">$</span>
                            <input type="number" step="0.01" name="price" value="{{ $price->price }}" style="width: 100px; padding: 0.5rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 4px; color: white;">
                            <button type="submit" class="btn btn-primary" style="padding: 0.5rem;">Save</button>
                        </form>
                    </td>
                    <td>Last updated: {{ $price->updated_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
