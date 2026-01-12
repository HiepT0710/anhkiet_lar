@extends('layouts.app')

@section('title', 'Thanh toán')

@push('styles')
<style>
    .payment-method-card {
        transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
    }
    .payment-method-card.active {
        border-color: #f97316 !important;
        background: #fff7ed !important;
        box-shadow: 0 0 0 1px rgba(249, 115, 22, 0.15);
    }
</style>
@endpush

@section('content')
<div class="container" style="padding: 30px 0;">
    <h1 style="margin-bottom:20px;">Thanh toán</h1>

    @if (session('error'))
        <div style="background:#fdecea;color:#c62828;padding:12px 16px;border-radius:6px;margin-bottom:16px;">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1.5fr);gap:24px;align-items:flex-start;">
        <form action="{{ route('checkout.vnpay') }}" method="POST" id="checkout-form" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            @csrf
            <h2 style="font-size:18px;margin-bottom:16px;">Thông tin thanh toán</h2>

            <div style="margin-bottom:18px;">
                <div style="font-weight:600;margin-bottom:8px;">Chọn phương thức thanh toán</div>

                {{-- COD --}}
                <label class="payment-method-card" style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;background:#f9fafb;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-truck" style="color:#dc2626;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;">Thanh toán khi nhận hàng (COD)</div>
                            <div style="font-size:12px;color:#6b7280;">Kiểm tra hàng xong rồi trả tiền</div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="cod" id="pm_cod" style="accent-color:#d32f2f;" checked>
                </label>

                {{-- QR ngân hàng (Bank QR) --}}
                <label class="payment-method-card" style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;background:#f9fafb;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#fff3cd;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-qrcode" style="color:#f97316;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;">QR ngân hàng (VietQR)</div>
                            <div style="font-size:12px;color:#92400e;">Quét mã QR để chuyển khoản vào tài khoản của cửa hàng</div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="bank_qr" id="pm_bank_qr" style="accent-color:#f97316;">
                </label>

                {{-- MoMo QR (dùng chung luồng QR nội bộ) --}}
                <label class="payment-method-card" style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;background:#f9fafb;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#ffe4ff;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-mobile-alt" style="color:#ec4899;"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;">Ví MoMo (quét QR)</div>
                            <div style="font-size:12px;color:#6b7280;">Quét mã QR MoMo để thanh toán nhanh</div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="momo_qr" id="pm_momo" style="accent-color:#ec4899;">
                </label>
                {{-- SePay QR --}}
                <label class="payment-method-card" style="display:flex;align-items:center;justify-content:space-between;border:1px solid #e5e7eb;border-radius:10px;padding:10px 14px;margin-bottom:8px;cursor:pointer;background:#f9fafb;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#e6f2ff;display:flex;align-items:center;justify-content:center;">
                            <img src="{{ asset('images/sepay-logo.png') }}" alt="SePay" style="width:20px;height:20px;">
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px;">SePay (quét QR)</div>
                            <div style="font-size:12px;color:#6b7280;">Quét mã QR SePay để thanh toán nhanh</div>
                        </div>
                    </div>
                    <input type="radio" name="payment_method" value="sepay_qr" id="pm_sepay" style="accent-color:#1e90ff;">
                </label>
            </div>

            <h2 style="font-size:18px;margin-bottom:16px;">Thông tin khách hàng</h2>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label for="customer_name" style="font-weight:600;">Họ và tên</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" required
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;margin-top:4px;">
                    @error('customer_name')
                    <div style="color:#dc2626;font-size:13px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="customer_phone" style="font-weight:600;">Số điện thoại</label>
                    <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;margin-top:4px;">
                    @error('customer_phone')
                    <div style="color:#dc2626;font-size:13px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="customer_email" style="font-weight:600;">Email (không bắt buộc)</label>
                    <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;margin-top:4px;">
                    @error('customer_email')
                    <div style="color:#dc2626;font-size:13px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="customer_address" style="font-weight:600;">Địa chỉ giao hàng</label>
                    <input type="text" id="customer_address" name="customer_address" value="{{ old('customer_address') }}" required
                           style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;margin-top:4px;">
                    @error('customer_address')
                    <div style="color:#dc2626;font-size:13px;">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="note" style="font-weight:600;">Ghi chú</label>
                    <textarea id="note" name="note" rows="3"
                              style="width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:6px;margin-top:4px;">{{ old('note') }}</textarea>
                    @error('note')
                    <div style="color:#dc2626;font-size:13px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit"
                    style="margin-top:18px;background:#d32f2f;color:#fff;border:none;padding:12px 24px;border-radius:6px;font-weight:600;cursor:pointer;">
                Xác nhận & thanh toán
            </button>
        </form>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <h2 style="font-size:18px;margin-bottom:16px;">Đơn hàng của bạn</h2>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($cart as $item)
                    <div style="display:flex;gap:10px;font-size:14px;border-bottom:1px solid #f3f4f6;padding-bottom:10px;">
                        @php
                            $imageUrl = $item['image_url'] ?? null;
                        @endphp
                        @if($imageUrl)
                            <div style="width:72px;height:72px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}" style="max-width:100%;max-height:100%;object-fit:contain;">
                            </div>
                        @endif
                        <div style="flex:1;display:flex;justify-content:space-between;gap:8px;">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                @if(!empty($item['selected_color']) || !empty($item['selected_size']))
                                    <div style="font-size:13px;color:#666;margin-top:2px;">
                                        @if(!empty($item['selected_color']))
                                            Màu: {{ $item['selected_color'] }}
                                        @endif
                                        @if(!empty($item['selected_size']))
                                            @if(!empty($item['selected_color'])) | @endif
                                            Phiên bản: {{ $item['selected_size'] }}
                                        @endif
                                    </div>
                                @endif
                                <div style="font-size:13px;color:#666;margin-top:2px;">Số lượng: {{ $item['quantity'] }}</div>
                            </div>
                            <div style="text-align:right;font-weight:600;white-space:nowrap;">
                                {{ number_format($item['price'] * $item['quantity']) }}đ
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr style="margin:16px 0;">
            
            <!-- Mã giảm giá -->
            <div style="margin-bottom:16px;padding:16px;background:#f8f9fa;border-radius:8px;">
                <div style="font-weight:600;margin-bottom:8px;font-size:14px;">Mã giảm giá</div>
                <div style="display:flex;gap:8px;">
                    <input type="text" id="coupon-code" placeholder="Nhập mã giảm giá" 
                           value="{{ $couponCode ?? '' }}"
                           style="flex:1;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;">
                    <button type="button" id="apply-coupon-btn" 
                            style="padding:10px 20px;background:#28a745;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;white-space:nowrap;">
                        Áp dụng
                    </button>
                </div>
                <div id="coupon-message" style="margin-top:8px;font-size:13px;"></div>
                @if($couponData)
                <div style="margin-top:8px;padding:8px;background:#d4edda;border-radius:6px;font-size:13px;color:#155724;">
                    <strong>✓ Đã áp dụng:</strong> {{ $couponData->code }}
                    @if($couponData->type === 'percent')
                        - Giảm {{ $couponData->value }}%
                    @else
                        - Giảm {{ number_format($couponData->value) }}đ
                    @endif
                    <button type="button" id="remove-coupon-btn" style="margin-left:8px;background:none;border:none;color:#155724;cursor:pointer;text-decoration:underline;">Xóa</button>
                </div>
                @endif
            </div>

            <!-- Tổng tiền -->
            @php
                $subtotal = $subtotal ?? $total;
                $discount = $discount ?? 0;
            @endphp
            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#666;">
                    <span>Tạm tính:</span>
                    <span>{{ number_format($subtotal) }}đ</span>
                </div>
                @if($discount > 0)
                <div style="display:flex;justify-content:space-between;font-size:14px;color:#28a745;">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($discount) }}đ</span>
                </div>
                @endif
                <hr style="margin:8px 0;">
                <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:700;">
                    <span>Tổng cộng:</span>
                    <span style="color:#d32f2f;">{{ number_format($total) }}đ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.payment-method-card');
    cards.forEach(card => {
        const input = card.querySelector('input[type="radio"]');
        if (!input) return;

        if (input.checked) {
            card.classList.add('active');
        }

        input.addEventListener('change', () => {
            cards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
        });

        // Click vào card cũng chọn radio
        card.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() !== 'input') {
                input.checked = true;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    // Apply Coupon
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    const couponCodeInput = document.getElementById('coupon-code');
    const couponMessage = document.getElementById('coupon-message');
    const removeCouponBtn = document.getElementById('remove-coupon-btn');

    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const code = couponCodeInput.value.trim().toUpperCase();
            if (!code) {
                couponMessage.innerHTML = '<span style="color:#dc2626;">Vui lòng nhập mã giảm giá</span>';
                return;
            }

            this.disabled = true;
            this.innerHTML = 'Đang kiểm tra...';
            couponMessage.innerHTML = '';

            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ coupon_code: code })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    couponMessage.innerHTML = '<span style="color:#28a745;">✓ ' + data.message + '</span>';
                    // Reload trang để cập nhật tổng tiền
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    couponMessage.innerHTML = '<span style="color:#dc2626;">✗ ' + data.message + '</span>';
                }
            })
            .catch(err => {
                couponMessage.innerHTML = '<span style="color:#dc2626;">Lỗi kết nối. Vui lòng thử lại.</span>';
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = 'Áp dụng';
            });
        });
    }

    // Remove Coupon
    if (removeCouponBtn) {
        removeCouponBtn.addEventListener('click', function() {
            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ coupon_code: '' })
            })
            .then(() => {
                window.location.reload();
            });
        });
    }

    // Enter key để apply coupon
    if (couponCodeInput) {
        couponCodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCouponBtn.click();
            }
        });
    }
});
</script>
@endpush

