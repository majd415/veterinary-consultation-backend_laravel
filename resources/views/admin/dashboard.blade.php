@extends('admin.layout')

@section('title', __('admin.dashboard'))

@section('content')
    <div class="grid-4">
        <div class="card">
            <div class="stat-label">{{ __('admin.total_users') }}</div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
        </div>
        <div class="card">
            <div class="stat-label">{{ __('admin.total_orders') }}</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
        </div>
        <div class="card">
            <div class="stat-label">{{ __('admin.products') }}</div>
            <div class="stat-value">{{ number_format($totalProducts) }}</div>
        </div>
        <div class="card">
            <div class="stat-label">{{ __('admin.total_revenue') }}</div>
            <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card">
            <h3 style="margin-top: 0;">Revenue Trend</h3>
            <canvas id="revenueChart" style="max-height: 300px;"></canvas>
        </div>
        <div class="card">
            <h3 style="margin-top: 0;">Order Status</h3>
            <canvas id="statusChart" style="max-height: 300px;"></canvas>
        </div>
        <div class="card">
            <h3 style="margin-top: 0;">Top Categories</h3>
            <canvas id="categoryChart" style="max-height: 300px;"></canvas>
        </div>
        <div class="card">
            <h3 style="margin-top: 0;">User Registrations</h3>
            <canvas id="userChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <div class="card">
        <h3>{{ __('admin.recent_orders') }}</h3>
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.products') }}</th>
                    <th>{{ __('admin.total_revenue') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->shipping_name }}</td>
                    <td>{{ $order->product ? (is_array($order->product->name) ? ($order->product->name['en'] ?? '') : $order->product->name) : 'N/A' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->status == 'delivered' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted);">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const neonViolet = '#8b5cf6';
        const neonCyan = '#06b6d4';
        const neonPink = '#ec4899';
        const neonAmber = '#f59e0b';
        const gridColor = 'rgba(255, 255, 255, 0.05)';

        // Common Chart Defaults
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // 1. Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($revenueData->pluck('month')),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($revenueData->pluck('total')),
                    borderColor: neonCyan,
                    backgroundColor: 'rgba(6, 182, 212, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: neonCyan,
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                scales: { y: { grid: { color: gridColor }, border: { display: false } }, x: { grid: { display: false } } },
                plugins: { legend: { display: false } }
            }
        });

        // 2. Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($statusData->pluck('status')),
                datasets: [{
                    data: @json($statusData->pluck('count')),
                    backgroundColor: [neonViolet, neonAmber, neonCyan, neonPink],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                cutout: '75%',
                plugins: { legend: { position: 'bottom', labels: { padding: 20 } } }
            }
        });

        // 3. Category Chart
        new Chart(document.getElementById('categoryChart'), {
            type: 'bar',
            data: {
                labels: @json($categoryData->pluck('name')),
                datasets: [{
                    label: 'Products',
                    data: @json($categoryData->pluck('products_count')),
                    backgroundColor: neonViolet,
                    borderRadius: 8,
                    hoverBackgroundColor: '#a78bfa'
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { grid: { color: gridColor } }, y: { grid: { display: false } } }
            }
        });

        // 4. User Chart
        new Chart(document.getElementById('userChart'), {
            type: 'line',
            data: {
                labels: @json($userData->pluck('month')),
                datasets: [{
                    label: 'New Users',
                    data: @json($userData->pluck('count')),
                    borderColor: neonPink,
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: neonPink
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { grid: { color: gridColor }, border: { display: false } }, x: { grid: { display: false } } }
            }
        });
    </script>
@endsection
