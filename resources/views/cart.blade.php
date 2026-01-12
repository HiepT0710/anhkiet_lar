@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container" style="padding: 30px 0;">
    <h1 style="margin-bottom:20px;">Giỏ hàng</h1>

    @if (session('success'))
        <div style="background:#e8f5e9;color:#2e7d32;padding:12px 16px;border-radius:6px;margin-bottom:16px;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:#fdecea;color:#c62828;padding:12px 16px;border-radius:6px;margin-bottom:16px;">{{ session('error') }}</div>
    @endif

    @if(empty($cart))
        <p>Giỏ hàng đang trống. <a href="/">Tiếp tục mua sắm</a></p>
    @else
        <table style="width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="text-align:left;padding:12px;">Sản phẩm</th>
                    <th style="text-align:center;padding:12px;">Giá</th>
                    <th style="text-align:center;padding:12px;">Số lượng</th>
                    <th style="text-align:right;padding:12px;">Thành tiền</th>
                    <th style="padding:12px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr style="border-top:1px solid #eee;">
                    <td style="padding:12px;display:flex;align-items:center;gap:12px;">
                        @php
                            // Ưu tiên dùng URL ảnh đã chuẩn hóa từ controller (image_url)
                            $cartImage = $item['image_url'] ?? null;
                        @endphp
                        @if(!empty($cartImage))
                            <a href="{{ route('product.show', $item['slug']) }}" style="display:inline-block;">
                                <img src="{{ $cartImage }}"
                                     alt="{{ $item['name'] }}"
                                     style="width:80px;height:80px;object-fit:cover;border-radius:4px;border:1px solid #eee;">
                            </a>
                        @endif
                        <div>
                            <a href="{{ route('product.show', $item['slug']) }}" style="text-decoration:none;color:#333;font-weight:600;">{{ $item['name'] }}</a>
                        @if(!empty($item['selected_color']) || !empty($item['selected_size']))
                            <div style="font-size:13px;color:#666;margin-top:4px;">
                                @if(!empty($item['selected_color']))
                                    <span>Màu: <strong>{{ $item['selected_color'] }}</strong></span>
                                @endif
                                @if(!empty($item['selected_size']))
                                    <span style="margin-left:10px;">Kích thước / dung lượng: <strong>{{ $item['selected_size'] }}</strong></span>
                                @endif
                            </div>
                        @endif
                        </div>
                    </td>
                    <td style="text-align:center;">{{ number_format($item['price']) }}đ</td>
                    <td style="text-align:center;">
                        <form action="{{ route('cart.update') }}" method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="row_id" value="{{ $item['row_id'] ?? ($item['id'] ?? '') }}">
                            <input type="number" min="1" name="quantity" value="{{ $item['quantity'] }}" style="width:70px;padding:6px 8px;border:1px solid #ddd;border-radius:4px;" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td style="text-align:right;">{{ number_format($item['price'] * $item['quantity']) }}đ</td>
                    <td style="text-align:center;">
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="row_id" value="{{ $item['row_id'] ?? ($item['id'] ?? '') }}">
                            <button type="submit" style="background:#e74c3c;color:#fff;border:none;padding:8px 12px;border-radius:4px;cursor:pointer;">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:20px;display:flex;justify-content:flex-end;gap:20px;align-items:center;">
            <div style="font-size:18px;">Tổng cộng: <strong style="color:#d32f2f;">{{ number_format($total) }}đ</strong></div>
            <a href="{{ route('checkout.show') }}"
               style="background:#d32f2f;color:#fff;border:none;padding:12px 24px;border-radius:6px;font-weight:600;cursor:pointer;text-decoration:none;">
                Thanh toán
            </a>
        </div>
    @endif
</div>
@endsection


