@extends('admin.layout')

@section('title', __('admin.orders'))

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.products') }}</th>
                    <th>{{ __('admin.users') }}</th>
                    <th>{{ __('admin.total_revenue') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.payments') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                        @if($order->product)
                            <div style="font-weight: bold;">{{ is_array($order->product->name) ? ($order->product->name['en'] ?? '') : $order->product->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); direction: rtl;">{{ is_array($order->product->name) ? ($order->product->name['ar'] ?? '') : '' }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-top: 4px;">
                                {{ is_array($order->product->description) ? ($order->product->description['en'] ?? '') : $order->product->description }}
                            </div>
                            <div style="font-size: 0.7rem; color: var(--text-muted); max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; direction: rtl;">
                                {{ is_array($order->product->description) ? ($order->product->description['ar'] ?? '') : '' }}
                            </div>
                        @else
                            <span class="badge badge-danger">Product #{{ $order->product_id }} removed</span>
                        @endif
                    </td>
                    <td>
                        <div><strong>{{ $order->shipping_name }}</strong></div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $order->shipping_phone }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $order->shipping_address }}</div>
                    </td>
                    <td>${{ $order->total_amount }}</td>
                    <td>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="display: inline;">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.3); color: white; border: 1px solid var(--border); border-radius: 4px; padding: 2px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                     <td>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="display: inline;">
                            @csrf @method('PUT')
                             <select name="payment_status" onchange="this.form.submit()" style="background: rgba(0,0,0,0.3); color: white; border: 1px solid var(--border); border-radius: 4px; padding: 2px;">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                         <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Delete this order?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">{{ $orders->links() }}</div>
    </div>
@endsection
