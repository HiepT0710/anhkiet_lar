@extends('layouts.admin')

@section('title', 'Thống kê bán hàng')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-bar"></i> Thống kê bán hàng</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn-primary">
        <i class="fas fa-list"></i> Danh sách đơn hàng
    </a>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
    .stat-card-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; }
    .stat-card-title { color: #7f8c8d; font-size: 14px; margin-bottom: 8px; }
    .stat-card-value { font-size: 28px; font-weight: 700; color: #2c3e50; }
    .stat-card-change { font-size: 13px; margin-top: 8px; }
    .chart-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 20px; }
    .chart-section h3 { margin: 0 0 20px; color: #2c3e50; font-size: 18px; }
    .chart-bar { height: 200px; display: flex; align-items: flex-end; gap: 8px; padding: 20px 0; }
    .chart-bar-item { flex: 1; background: linear-gradient(180deg, #3498db, #2980b9); border-radius: 6px 6px 0 0; position: relative; min-height: 20px; transition: all 0.3s; }
    .chart-bar-item:hover { opacity: 0.8; }
    .chart-bar-label { text-align: center; font-size: 11px; color: #666; margin-top: 8px; }
    .chart-bar-value { position: absolute; top: -25px; left: 50%; transform: translateX(-50%); font-size: 11px; font-weight: 600; color: #2c3e50; white-space: nowrap; }
    .top-products { }
    .top-product-item { display: flex; align-items: center; gap: 15px; padding: 12px 0; border-bottom: 1px solid #eee; }
    .top-product-item:last-child { border-bottom: none; }
    .top-product-rank { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }
    .top-product-rank.gold { background: #ffd700; color: #333; }
    .top-product-rank.silver { background: #c0c0c0; color: #333; }
    .top-product-rank.bronze { background: #cd7f32; color: #fff; }
    .top-product-rank.normal { background: #eee; color: #666; }
    .top-product-info { flex: 1; }
    .top-product-name { font-weight: 600; margin-bottom: 4px; }
    .top-product-stats { font-size: 13px; color: #666; }
    .status-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; }
    .status-item { padding: 15px; border-radius: 8px; text-align: center; }
    .status-item-value { font-size: 24px; font-weight: 700; margin-bottom: 5px; }
    .status-item-label { font-size: 13px; }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Thống kê tổng quan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#e3f2fd;color:#1976d2;">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-card-title">Tổng đơn hàng</div>
        <div class="stat-card-value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#e8f5e9;color:#388e3c;">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title">Tổng doanh thu</div>
        <div class="stat-card-value">{{ number_format($totalRevenue) }}đ</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#fff3e0;color:#f57c00;">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-card-title">Sản phẩm đã bán</div>
        <div class="stat-card-value">{{ number_format($totalProductsSold) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:#fce4ec;color:#c2185b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-card-title">Đơn chờ xử lý</div>
        <div class="stat-card-value">{{ number_format($pendingOrders) }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
    <div>
        <!-- Biểu đồ doanh thu 7 ngày -->
        <div class="chart-section">
            <h3><i class="fas fa-chart-line"></i> Doanh thu 7 ngày gần nhất</h3>
            @php
                $maxRevenue = $last7Days->max('revenue') ?: 1;
            @endphp
            <div class="chart-bar">
                @foreach($last7Days as $day)
                <div style="flex:1;text-align:center;">
                    <div class="chart-bar-item" style="height:{{ ($day['revenue'] / $maxRevenue) * 180 }}px;">
                        @if($day['revenue'] > 0)
                        <div class="chart-bar-value">{{ number_format($day['revenue']) }}đ</div>
                        @endif
                    </div>
                    <div class="chart-bar-label">{{ $day['date'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Thống kê theo trạng thái -->
        <div class="chart-section">
            <h3><i class="fas fa-tasks"></i> Thống kê theo trạng thái đơn hàng</h3>
            <div class="status-grid">
                <div class="status-item" style="background:#fff3e0;">
                    <div class="status-item-value" style="color:#ff9800;">{{ $ordersByStatus['pending'] ?? 0 }}</div>
                    <div class="status-item-label">Chờ xử lý</div>
                </div>
                <div class="status-item" style="background:#e3f2fd;">
                    <div class="status-item-value" style="color:#2196f3;">{{ $ordersByStatus['processing'] ?? 0 }}</div>
                    <div class="status-item-label">Đang xử lý</div>
                </div>
                <div class="status-item" style="background:#e0f7fa;">
                    <div class="status-item-value" style="color:#00bcd4;">{{ $ordersByStatus['shipping'] ?? 0 }}</div>
                    <div class="status-item-label">Đang giao</div>
                </div>
                <div class="status-item" style="background:#e8f5e9;">
                    <div class="status-item-value" style="color:#4caf50;">{{ $ordersByStatus['completed'] ?? 0 }}</div>
                    <div class="status-item-label">Hoàn thành</div>
                </div>
                <div class="status-item" style="background:#ffebee;">
                    <div class="status-item-value" style="color:#f44336;">{{ $ordersByStatus['cancelled'] ?? 0 }}</div>
                    <div class="status-item-label">Đã hủy</div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo PTTT -->
        <div class="chart-section">
            <h3><i class="fas fa-credit-card"></i> Phương thức thanh toán</h3>
            <div class="status-grid">
                @php
                    $paymentMethods = [
                        'cod' => ['label' => 'COD', 'color' => '#ff9800', 'bg' => '#fff3e0'],
                        'bank_qr' => ['label' => 'QR Ngân hàng', 'color' => '#2196f3', 'bg' => '#e3f2fd'],
                        'momo_qr' => ['label' => 'MoMo', 'color' => '#e91e63', 'bg' => '#fce4ec'],
                        'vnpay' => ['label' => 'VNPay', 'color' => '#00bcd4', 'bg' => '#e0f7fa'],
                    ];
                @endphp
                @foreach($paymentMethods as $key => $method)
                <div class="status-item" style="background:{{ $method['bg'] }};">
                    <div class="status-item-value" style="color:{{ $method['color'] }};">{{ $ordersByPaymentMethod[$key] ?? 0 }}</div>
                    <div class="status-item-label">{{ $method['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div>
        <!-- Top sản phẩm bán chạy -->
        <div class="chart-section">
            <h3><i class="fas fa-trophy"></i> Top sản phẩm bán chạy</h3>
            <div class="top-products">
                @forelse($topProducts as $index => $product)
                <div class="top-product-item">
                    <div class="top-product-rank {{ $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal')) }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="top-product-info">
                        <div class="top-product-name">{{ \Illuminate\Support\Str::limit($product->product_name, 30) }}</div>
                        <div class="top-product-stats">
                            <strong>{{ $product->total_sold }}</strong> đã bán • 
                            <span style="color:#e74c3c;">{{ number_format($product->total_revenue) }}đ</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;color:#999;padding:30px;">
                    Chưa có dữ liệu
                </div>
                @endforelse
            </div>
        </div>

        <!-- Đơn hàng gần đây -->
        <div class="chart-section">
            <h3><i class="fas fa-history"></i> Đơn hàng gần đây</h3>
            @forelse($recentOrders as $order)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #eee;">
                <div>
                    <div style="font-weight:600;color:#3498db;">{{ $order->code }}</div>
                    <div style="font-size:12px;color:#666;">{{ $order->customer_name }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:600;color:#e74c3c;">{{ number_format($order->total_amount) }}đ</div>
                    <div style="font-size:11px;color:#999;">{{ $order->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;color:#999;padding:30px;">
                Chưa có đơn hàng
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

