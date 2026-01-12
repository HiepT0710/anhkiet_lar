@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->code)

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        @include('account.sidebar')

        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h1 style="margin: 0; color: #2c3e50;">Đơn hàng #{{ $order->code }}</h1>
                <a href="{{ route('account.orders') }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
                <!-- Sản phẩm -->
                <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                        <i class="fas fa-box"></i> Sản phẩm đã đặt ({{ $order->items->sum('quantity') }} sản phẩm)
                    </h3>
                    @foreach($order->items as $item)
                    <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #eee;">
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
                        <img src="{{ $productImage }}" alt="{{ $item->product_name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                        @else
                        <div style="width: 60px; height: 60px; background: #f5f5f5; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px;">No Image</div>
                        @endif
                        <div style="flex: 1;">
                            <div style="font-weight: 600; margin-bottom: 4px;">
                                @if($item->product)
                                <a href="{{ route('product.show', $item->product->slug) }}" style="color: #2563eb; text-decoration: none;">{{ $item->product_name }}</a>
                                @else
                                {{ $item->product_name }}
                                @endif
                            </div>
                            <div style="font-size: 13px; color: #666;">
                                Số lượng: {{ $item->quantity }}
                                @if($item->selected_color) • Màu: {{ $item->selected_color }} @endif
                                @if($item->selected_size) • Size: {{ $item->selected_size }} @endif
                            </div>
                        </div>
                        <div style="text-align: right; font-weight: 600; color: #e74c3c;">
                            {{ number_format($item->total_price) }}đ
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Thông tin đơn hàng -->
                <div>
                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                            <i class="fas fa-info-circle"></i> Thông tin đơn hàng
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Trạng thái:</span>
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
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Thanh toán:</span>
                                <span style="font-weight: 600; color: {{ $order->payment_status === 'paid' ? '#4caf50' : '#f57c00' }};">
                                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Phương thức:</span>
                                <span style="font-weight: 600;">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Ngày đặt:</span>
                                <span style="font-weight: 600;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                            <i class="fas fa-user"></i> Thông tin giao hàng
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 8px; color: #555;">
                            <div><strong>Người nhận:</strong> {{ $order->customer_name }}</div>
                            <div><strong>Điện thoại:</strong> {{ $order->customer_phone }}</div>
                            <div><strong>Email:</strong> {{ $order->customer_email }}</div>
                            <div><strong>Địa chỉ:</strong> {{ $order->customer_address }}</div>
                            @if($order->note)
                            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                                <strong>Ghi chú:</strong> {{ $order->note }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 20px 0; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
                            <i class="fas fa-receipt"></i> Tổng thanh toán
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Tạm tính:</span>
                                <span style="font-weight: 600;">{{ number_format($order->subtotal_amount) }}đ</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <span style="color: #7f8c8d;">Giảm giá:</span>
                                <span style="font-weight: 600; color: #4caf50;">-{{ number_format($order->discount_amount) }}đ</span>
                            </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; padding: 15px 0; border-top: 2px solid #eee; margin-top: 10px;">
                                <span style="font-size: 18px; font-weight: 700; color: #2c3e50;">Tổng cộng:</span>
                                <span style="font-size: 20px; font-weight: 700; color: #e74c3c;">{{ number_format($order->total_amount) }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

