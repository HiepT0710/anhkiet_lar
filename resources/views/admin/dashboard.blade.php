@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .stat-card-icon { font-size: 40px; color: #3498db; margin-bottom: 15px; }
    .stat-card-title { color: #7f8c8d; font-size: 14px; margin-bottom: 5px; }
    .stat-card-value { font-size: 32px; font-weight: 700; color: #2c3e50; }
    .recent-section { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .recent-section h2 { margin-bottom: 20px; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; }
    table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
    table th { background: #ecf0f1; font-weight: 600; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .badge-success { background: #2ecc71; color: white; }
    .badge-danger { background: #e74c3c; color: white; }
</style>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon" style="color: #e74c3c;"><i class="fas fa-shopping-cart"></i></div>
        <div class="stat-card-title">Tổng đơn hàng</div>
        <div class="stat-card-value">{{ $stats['totalOrders'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color: #27ae60;"><i class="fas fa-money-bill-wave"></i></div>
        <div class="stat-card-title">Doanh thu</div>
        <div class="stat-card-value">{{ number_format(($stats['totalRevenue'] ?? 0) / 1000000, 1) }}M</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color: #f39c12;"><i class="fas fa-clock"></i></div>
        <div class="stat-card-title">Đơn chờ xử lý</div>
        <div class="stat-card-value">{{ $stats['pendingOrders'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color: #3498db;"><i class="fas fa-box-open"></i></div>
        <div class="stat-card-title">SP đã bán</div>
        <div class="stat-card-value">{{ $stats['totalProductsSold'] ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon"><i class="fas fa-box"></i></div>
        <div class="stat-card-title">Tổng sản phẩm</div>
        <div class="stat-card-value">{{ $stats['totalProducts'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="color: #9b59b6;"><i class="fas fa-users"></i></div>
        <div class="stat-card-title">Người dùng</div>
        <div class="stat-card-value">{{ $stats['totalUsers'] }}</div>
    </div>
</div>

<!-- Recent Products -->
<div class="recent-section">
    <h2>Sản phẩm gần đây</h2>
    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->final_price) }}đ</td>
                <td>
                    @if($product->is_active)
                        <span class="badge badge-success">Đang bán</span>
                    @else
                        <span class="badge badge-danger">Tạm ngưng</span>
                    @endif
                </td>
                <td>{{ $product->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Chưa có sản phẩm</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Recent Orders -->
<div class="recent-section">
    <h2>Đơn hàng gần đây</h2>
    <table>
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày đặt</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders ?? [] as $order)
            <tr>
                <td><a href="{{ route('admin.orders.show', $order) }}" style="color:#3498db;font-weight:600;">{{ $order->code }}</a></td>
                <td>{{ $order->customer_name }}</td>
                <td style="color:#e74c3c;font-weight:600;">{{ number_format($order->total_amount) }}đ</td>
                <td>
                    @php
                        $statusColors = ['pending' => '#ff9800', 'processing' => '#2196f3', 'shipping' => '#00bcd4', 'completed' => '#4caf50', 'cancelled' => '#f44336'];
                        $statusLabels = ['pending' => 'Chờ xử lý', 'processing' => 'Đang xử lý', 'shipping' => 'Đang giao', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'];
                    @endphp
                    <span class="badge" style="background:{{ $statusColors[$order->status] ?? '#999' }};color:#fff;">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Chưa có đơn hàng</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:15px;text-align:right;">
        <a href="{{ route('admin.orders.index') }}" style="color:#3498db;">Xem tất cả đơn hàng →</a>
    </div>
</div>

<!-- Recent Banners -->
<div class="recent-section">
    <h2>Banners gần đây</h2>
    <table>
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Vị trí</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentBanners as $banner)
            <tr>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->position }}</td>
                <td>
                    @if($banner->is_active)
                        <span class="badge badge-success">Đang hiển thị</span>
                    @else
                        <span class="badge badge-danger">Tạm ẩn</span>
                    @endif
                </td>
                <td>{{ $banner->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4">Chưa có banner</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
