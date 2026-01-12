@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->code)

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-invoice"></i> Đơn hàng #{{ $order->code }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn-primary">← Quay lại</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
    .order-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    .order-card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .order-card h3 { margin: 0 0 20px; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
    .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #7f8c8d; }
    .info-value { font-weight: 600; color: #2c3e50; }
    .product-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #eee; }
    .product-item:last-child { border-bottom: none; }
    .product-img { width: 60px; height: 60px; background: #f5f5f5; border-radius: 8px; object-fit: cover; }
    .product-details { flex: 1; }
    .product-name { font-weight: 600; margin-bottom: 4px; }
    .product-meta { font-size: 13px; color: #666; }
    .product-price { text-align: right; font-weight: 600; color: #e74c3c; }
    .status-form { display: flex; gap: 10px; align-items: center; }
    .status-form select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; }
    .status-form button { padding: 8px 16px; background: #3498db; color: white; border: none; border-radius: 6px; cursor: pointer; }
</style>

<div class="order-grid">
    <div>
        <!-- Sản phẩm -->
        <div class="order-card">
            <h3><i class="fas fa-box"></i> Sản phẩm đã đặt ({{ $order->items->sum('quantity') }} sản phẩm)</h3>
            @foreach($order->items as $item)
            <div class="product-item">
                @php
                    $productImage = null;
                    if ($item->product) {
                        $images = $item->product->images;
                        if (is_string($images)) {
                            $images = json_decode($images, true);
                        }
                        $productImage = is_array($images) && count($images) > 0 ? asset('storage/' . $images[0]) : null;
                    }
                @endphp
                @if($productImage)
                    <img src="{{ $productImage }}" alt="{{ $item->product_name }}" class="product-img">
                @else
                    <div class="product-img" style="display:flex;align-items:center;justify-content:center;font-size:20px;color:#ccc;">
                        <i class="fas fa-image"></i>
                    </div>
                @endif
                <div class="product-details">
                    <div class="product-name">{{ $item->product_name }}</div>
                    <div class="product-meta">
                        @if($item->selected_color)
                            Màu: {{ $item->selected_color }}
                        @endif
                        @if($item->selected_size)
                            @if($item->selected_color) | @endif
                            Phiên bản: {{ $item->selected_size }}
                        @endif
                    </div>
                    <div class="product-meta">
                        Số lượng: {{ $item->quantity }} x {{ number_format($item->unit_price) }}đ
                    </div>
                </div>
                <div class="product-price">
                    {{ number_format($item->total_price) }}đ
                </div>
            </div>
            @endforeach

            <div style="margin-top:20px;padding-top:20px;border-top:2px solid #eee;">
                <div class="info-row">
                    <span class="info-label">Tạm tính:</span>
                    <span class="info-value">{{ number_format($order->items->sum('total_price')) }}đ</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phí vận chuyển:</span>
                    <span class="info-value">0đ</span>
                </div>
                <div class="info-row" style="font-size:18px;">
                    <span class="info-label"><strong>Tổng cộng:</strong></span>
                    <span class="info-value" style="color:#e74c3c;font-size:20px;">{{ number_format($order->total_amount) }}đ</span>
                </div>
            </div>
        </div>

        <!-- Ghi chú -->
        @if($order->note)
        <div class="order-card">
            <h3><i class="fas fa-sticky-note"></i> Ghi chú</h3>
            <p style="margin:0;color:#666;">{{ $order->note }}</p>
        </div>
        @endif
    </div>

    <div>
        <!-- Thông tin khách hàng -->
        <div class="order-card">
            <h3><i class="fas fa-user"></i> Khách hàng</h3>
            <div class="info-row">
                <span class="info-label">Họ tên:</span>
                <span class="info-value">{{ $order->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SĐT:</span>
                <span class="info-value">{{ $order->customer_phone }}</span>
            </div>
            @if($order->customer_email)
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->customer_email }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">Địa chỉ:</span>
                <span class="info-value">{{ $order->customer_address }}</span>
            </div>
            @if($order->user)
            <div class="info-row">
                <span class="info-label">Tài khoản:</span>
                <span class="info-value">{{ $order->user->email }}</span>
            </div>
            @endif
        </div>

        <!-- Thanh toán -->
        <div class="order-card">
            <h3><i class="fas fa-credit-card"></i> Thanh toán</h3>
            <div class="info-row">
                <span class="info-label">Phương thức:</span>
                <span class="info-value">
                    @php
                        $paymentMethods = [
                            'cod' => 'Thanh toán khi nhận hàng',
                            'bank_qr' => 'QR Ngân hàng',
                            'momo_qr' => 'Ví MoMo',
                            'vnpay' => 'VNPay',
                        ];
                    @endphp
                    {{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value">
                    @php
                        $paymentColors = [
                            'pending' => '#ff9800',
                            'paid' => '#4caf50',
                            'failed' => '#f44336',
                            'refunded' => '#9c27b0',
                        ];
                        $paymentLabels = [
                            'pending' => 'Chưa thanh toán',
                            'paid' => 'Đã thanh toán',
                            'failed' => 'Thất bại',
                            'refunded' => 'Đã hoàn tiền',
                        ];
                    @endphp
                    <span class="badge" style="background:{{ $paymentColors[$order->payment_status] ?? '#999' }};color:#fff;">
                        {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                    </span>
                </span>
            </div>
            <form action="{{ route('admin.orders.payment-status', $order) }}" method="POST" class="status-form" style="margin-top:12px;">
                @csrf
                @method('PATCH')
                <select name="payment_status">
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        </div>

        <!-- Trạng thái đơn hàng -->
        <div class="order-card">
            <h3><i class="fas fa-truck"></i> Trạng thái đơn hàng</h3>
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value">
                    @php
                        $statusColors = [
                            'pending' => '#ff9800',
                            'processing' => '#2196f3',
                            'shipping' => '#00bcd4',
                            'completed' => '#4caf50',
                            'cancelled' => '#f44336',
                        ];
                        $statusLabels = [
                            'pending' => 'Chờ xử lý',
                            'processing' => 'Đang xử lý',
                            'shipping' => 'Đang giao hàng',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                        ];
                    @endphp
                    <span class="badge" style="background:{{ $statusColors[$order->status] ?? '#999' }};color:#fff;">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày đặt:</span>
                <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="status-form" style="margin-top:12px;">
                @csrf
                @method('PATCH')
                <select name="status">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection

