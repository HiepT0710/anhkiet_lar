@extends('layouts.app')

@section('title', 'Thanh toán - Quét QR')

@section('content')
<div class="container" style="padding: 30px 0;">
    <h1 style="margin-bottom:20px;">Thanh toán - Quét mã QR</h1>

    @if (session('error'))
        <div style="background:#fdecea;color:#c62828;padding:12px 16px;border-radius:6px;margin-bottom:16px;">{{ session('error') }}</div>
    @endif

    <div style="background:#fff;padding:24px;border-radius:8px;max-width:720px;margin:0 auto;text-align:center;">
        <h3 style="margin-bottom:8px;">{{ $methodLabel }}</h3>
        <p style="color:#6b7280;margin-bottom:16px;">{{ $helperText }}</p>

        @if($qrImage)
            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                <img src="{{ $qrImage }}" alt="QR Payment" style="width:320px;height:320px;object-fit:contain;border:1px solid #f1f5f9;padding:8px;background:#fff;">
                <div style="font-weight:700;color:#d32f2f;font-size:18px;">{{ number_format($order->total_amount) }}đ</div>
                <div style="font-size:13px;color:#6b7280;">
                    Mã đơn hàng: <strong>{{ $order->code }}</strong>
                </div>
                @if(!empty($sepay_debug))
                    <div style="margin-top:12px;padding:10px;background:#fff8e1;border:1px solid #ffecb3;border-radius:6px;max-width:720px;word-break:break-all;text-align:left;">
                        <strong>DEBUG SePay:</strong>
                        <pre style="white-space:pre-wrap;margin:6px 0;">{{ print_r($sepay_debug, true) }}</pre>
                    </div>
                @endif
                <div style="display:flex;gap:8px;">
                    <button id="copy-order-code" style="background:#2563eb;color:#fff;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;">Sao chép mã đơn</button>
                    <a href="{{ route('account.orders') }}" style="background:#e5e7eb;color:#111;padding:8px 12px;border-radius:6px;text-decoration:none;">Quay lại đơn hàng</a>
                </div>
                @if(!empty($payment_url))
                    <div style="margin-top:8px;">
                        <a href="{{ $payment_url }}" target="_blank" style="color:#1e90ff;text-decoration:underline;">Mở trang thanh toán SePay</a>
                    </div>
                @endif
            </div>
        @else
            <div style="padding:24px;">
                <p>Không có mã QR để hiển thị. Vui lòng liên hệ quản trị.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.getElementById('copy-order-code');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            const text = '{{ $order->code }}';
            navigator.clipboard?.writeText(text).then(function() {
                alert('Đã sao chép mã đơn: ' + text);
            }).catch(function() {
                prompt('Sao chép mã đơn:', text);
            });
        });
    }
});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Quét mã QR thanh toán')

@section('content')
<div class="container" style="padding: 30px 0;">
    <h1 style="margin-bottom:20px;">Thanh toán đơn hàng {{ $order->code }}</h1>

    <div style="display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1.5fr);gap:24px;align-items:flex-start;">
        <div style="background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(15,23,42,0.08);text-align:center;">
            <h2 style="font-size:18px;margin-bottom:10px;">{{ $methodLabel }}</h2>
            <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">{{ $helperText }}</p>

            <div style="display:inline-block;padding:12px;border-radius:16px;background:#f9fafb;margin-bottom:16px;">
                <img src="{{ $qrImage }}" alt="QR thanh toán" style="width:260px;height:260px;object-fit:contain;">
            </div>

            <div style="margin-top:10px;font-size:14px;color:#4b5563;">
                <p><strong>Số tiền cần thanh toán:</strong>
                    <span style="color:#dc2626;font-size:18px;font-weight:700;">{{ number_format($order->total_amount) }}đ</span>
                </p>
                <p style="margin-top:6px;">
                    <strong>Nội dung chuyển khoản:</strong>
                    <span style="background:#f3f4f6;border-radius:999px;padding:4px 10px;">{{ $order->code }} {{ $order->customer_phone }}</span>
                </p>
            </div>

            <p style="font-size:12px;color:#9ca3af;margin-top:14px;">
                Sau khi thanh toán, vui lòng giữ lại biên lai để đối chiếu khi cần.
            </p>
        </div>

        <div style="background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(15,23,42,0.08);">
            <h2 style="font-size:18px;margin-bottom:12px;">Thông tin đơn hàng</h2>
            <p style="font-size:14px;margin-bottom:4px;"><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
            <p style="font-size:14px;margin-bottom:4px;"><strong>SĐT:</strong> {{ $order->customer_phone }}</p>
            <p style="font-size:14px;margin-bottom:10px;"><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>

            <div style="border-top:1px solid #e5e7eb;margin:10px 0 12px;"></div>

            <ul style="list-style:none;padding:0;margin:0;font-size:14px;">
                @foreach($order->items as $item)
                    <li style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <div>
                            <strong>{{ $item->product_name }}</strong>
                            @if($item->selected_color || $item->selected_size)
                                <div style="font-size:13px;color:#6b7280;">
                                    @if($item->selected_color)
                                        Màu: {{ $item->selected_color }}
                                    @endif
                                    @if($item->selected_size)
                                        @if($item->selected_color) | @endif
                                        Phiên bản: {{ $item->selected_size }}
                                    @endif
                                </div>
                            @endif
                            <div style="font-size:13px;color:#6b7280;">Số lượng: {{ $item->quantity }}</div>
                        </div>
                        <div style="text-align:right;white-space:nowrap;">
                            {{ number_format($item->total_price) }}đ
                        </div>
                    </li>
                @endforeach
            </ul>

            <div style="border-top:1px solid #e5e7eb;margin:10px 0 12px;"></div>

            <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:700;">
                <span>Tổng cộng</span>
                <span style="color:#dc2626;">{{ number_format($order->total_amount) }}đ</span>
            </div>

            <a href="{{ route('home') }}" style="display:inline-block;margin-top:18px;font-size:14px;color:#2563eb;text-decoration:none;">
                &larr; Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection


