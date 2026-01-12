@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-shopping-cart"></i> Quản lý đơn hàng</h1>
    <a href="{{ route('admin.orders.statistics') }}" class="btn-primary">
        <i class="fas fa-chart-bar"></i> Thống kê bán hàng
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Filters -->
<div class="table-section" style="margin-bottom:20px;">
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã đơn, tên, SĐT..."
                   style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;width:200px;">
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:4px;">Trạng thái</label>
            <select name="status" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:4px;">Thanh toán</label>
            <select name="payment_status" style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
            </select>
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:4px;">Từ ngày</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                   style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;">
        </div>
        <div>
            <label style="display:block;font-size:12px;color:#666;margin-bottom:4px;">Đến ngày</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                   style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;">
        </div>
        <button type="submit" class="btn-primary" style="padding:8px 16px;">
            <i class="fas fa-search"></i> Lọc
        </button>
        <a href="{{ route('admin.orders.index') }}" style="padding:8px 16px;background:#6c757d;color:#fff;border-radius:6px;text-decoration:none;">
            <i class="fas fa-times"></i> Reset
        </a>
    </form>
</div>

<div class="table-section">
    <table>
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Sản phẩm</th>
                <th style="text-align:right;">Tổng tiền</th>
                <th style="text-align:center;">Thanh toán</th>
                <th style="text-align:center;">Trạng thái</th>
                <th style="text-align:center;">Ngày đặt</th>
                <th style="text-align:center;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    <strong style="color:#3498db;">{{ $order->code }}</strong>
                </td>
                <td>
                    <strong>{{ $order->customer_name }}</strong>
                    <div style="font-size:12px;color:#666;">{{ $order->customer_phone }}</div>
                </td>
                <td>
                    <span style="font-size:13px;">{{ $order->items->count() }} sản phẩm</span>
                    <div style="font-size:12px;color:#666;">
                        {{ $order->items->sum('quantity') }} items
                    </div>
                </td>
                <td style="text-align:right;">
                    <strong style="color:#e74c3c;">{{ number_format($order->total_amount) }}đ</strong>
                </td>
                <td style="text-align:center;">
                    @php
                        $paymentColors = [
                            'pending' => '#ff9800',
                            'paid' => '#4caf50',
                            'failed' => '#f44336',
                            'refunded' => '#9c27b0',
                        ];
                        $paymentLabels = [
                            'pending' => 'Chờ TT',
                            'paid' => 'Đã TT',
                            'failed' => 'Thất bại',
                            'refunded' => 'Hoàn tiền',
                        ];
                    @endphp
                    <span class="badge" style="background:{{ $paymentColors[$order->payment_status] ?? '#999' }};color:#fff;">
                        {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                    </span>
                </td>
                <td style="text-align:center;">
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
                            'shipping' => 'Đang giao',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                        ];
                    @endphp
                    <span class="badge" style="background:{{ $statusColors[$order->status] ?? '#999' }};color:#fff;">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td style="text-align:center;font-size:13px;">
                    {{ $order->created_at->format('d/m/Y') }}
                    <div style="font-size:11px;color:#999;">{{ $order->created_at->format('H:i') }}</div>
                </td>
                <td style="text-align:center;">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-edit">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#999;">
                    Chưa có đơn hàng nào.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div class="pagination">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

