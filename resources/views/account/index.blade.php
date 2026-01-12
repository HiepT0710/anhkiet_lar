@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        @include('account.sidebar')

        <!-- Main Content -->
        <div>
            <h1 style="margin-bottom: 30px; color: #2c3e50;">Tổng quan tài khoản</h1>

            @if(session('success'))
            <div style="background: #e8f5e9; color: #2e7d32; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            <!-- Thống kê -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #e3f2fd; color: #1976d2; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div>
                            <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Tổng đơn hàng</div>
                            <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ $totalOrders }}</div>
                        </div>
                    </div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #fff3e0; color: #f57c00; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Đơn chờ xử lý</div>
                            <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ $pendingOrders }}</div>
                        </div>
                    </div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #fff3e0; color: #ffc107; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Đánh giá</div>
                            <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ $totalReviews }}</div>
                        </div>
                    </div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: #fce4ec; color: #e91e63; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Yêu thích</div>
                            <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ $totalWishlist }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng gần đây -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Đơn hàng gần đây</h2>
                @if($recentOrders->count() > 0)
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
                        @foreach($recentOrders as $order)
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
                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('account.orders') }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
                        Xem tất cả đơn hàng <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                @else
                <p style="text-align: center; color: #999; padding: 40px;">Chưa có đơn hàng nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

