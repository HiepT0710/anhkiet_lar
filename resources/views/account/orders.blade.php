@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        @include('account.sidebar')

        <div>
            <h1 style="margin-bottom: 30px; color: #2c3e50;">Đơn hàng của tôi</h1>

            @if($orders->count() > 0)
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 12px; text-align: left; font-weight: 600;">Mã đơn</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600;">Ngày đặt</th>
                            <th style="padding: 12px; text-align: right; font-weight: 600;">Tổng tiền</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600;">Trạng thái</th>
                            <th style="padding: 12px; text-align: center; font-weight: 600;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px;">{{ $order->code }}</td>
                            <td style="padding: 12px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td style="padding: 12px; text-align: right; font-weight: 600; color: #d32f2f;">{{ number_format($order->total_amount) }}đ</td>
                            <td style="padding: 12px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'pending' => ['bg' => '#fff3e0', 'color' => '#f57c00', 'text' => 'Chờ xử lý'],
                                        'processing' => ['bg' => '#e3f2fd', 'color' => '#2196f3', 'text' => 'Đang xử lý'],
                                        'shipping' => ['bg' => '#e0f7fa', 'color' => '#00bcd4', 'text' => 'Đang giao'],
                                        'completed' => ['bg' => '#e8f5e9', 'color' => '#4caf50', 'text' => 'Hoàn thành'],
                                        'cancelled' => ['bg' => '#ffebee', 'color' => '#f44336', 'text' => 'Đã hủy'],
                                    ];
                                    $status = $statusColors[$order->status] ?? $statusColors['pending'];
                                @endphp
                                <span style="padding: 5px 12px; border-radius: 20px; background: {{ $status['bg'] }}; color: {{ $status['color'] }}; font-weight: 600; font-size: 12px;">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="{{ route('account.order-detail', $order) }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top: 20px;">
                    {{ $orders->links() }}
                </div>
            </div>
            @else
            <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-shopping-bag" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                <p style="color: #999;">Bạn chưa có đơn hàng nào</p>
                <a href="{{ route('home') }}" style="display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: none; font-weight: 600;">
                    Tiếp tục mua sắm <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

