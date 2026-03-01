@extends('admin.layout')

@section('title', __('admin.payments'))

@section('content')
    <!-- Stats Row -->
    <div class="grid-4">
        <div class="card" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="stat-label" style="color: white;">{{ __('admin.total_revenue') }}</div>
            <div class="stat-value" style="color: white;">${{ number_format($totalSuccess, 2) }}</div>
        </div>
        
        @if(Auth::user()->isSuperAdmin())
        <!-- Stripe Config -->
        <div class="card" style="grid-column: span 2;">
            <h3>{{ __('admin.settings') }}</h3>
            <form action="{{ route('admin.payments.settings.update') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 1rem; align-items: flex-end;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Secret Key</label>
                        <input type="password" name="stripe_secret_key" value="{{ $stripeKey ?? '' }}" style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 0.5rem; color: white;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Publishable Key</label>
                        <input type="text" name="stripe_publishable_key" value="{{ $stripePub ?? '' }}" style="width: 100%; padding: 0.5rem; background: rgba(0,0,0,0.2); border: 1px solid var(--border); border-radius: 0.5rem; color: white;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 40px;">Update</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <!-- Payment Logs -->
    <div class="card">
        <h3>{{ __('admin.payments') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $pay)
                <tr>
                    <td style="font-family: monospace;">{{ $pay->transaction_id ?? '-' }}</td>
                    <td>user_id: {{ $pay->user_id }}</td>
                    <td>{{ $pay->type }}</td>
                    <td style="font-weight: bold; color: #10b981;">${{ $pay->amount }}</td>
                    <td>{{ $pay->payment_method }}</td>
                    <td>
                         <span class="badge {{ $pay->status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($pay->status) }}
                        </span>
                    </td>
                    <td>{{ $pay->created_at->format('M d, H:i') }}</td>
                    <td>
                         <form action="{{ route('admin.payments.destroy', $pay->id) }}" method="POST" onsubmit="return confirm('Delete this log?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-icon">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
         <div style="margin-top: 1rem;">{{ $payments->links() }}</div>
    </div>
@endsection
