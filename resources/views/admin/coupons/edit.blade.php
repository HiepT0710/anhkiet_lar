@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Sửa mã giảm giá</h1>
    <a href="{{ route('coupons.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div style="background:#fdecea;color:#c62828;padding:12px 16px;border-radius:6px;margin-bottom:20px;">
        <strong>Có lỗi xảy ra:</strong>
        <ul style="margin:8px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="table-section">
    <form action="{{ route('coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            <!-- Code -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Mã giảm giá <span style="color:#e74c3c;">*</span>
                </label>
                <input name="code" required value="{{ old('code',$coupon->code) }}" 
                       placeholder="VD: SALE10"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Chữ in hoa, không dấu cách</small>
                @error('code')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kiểu -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Loại giảm giá <span style="color:#e74c3c;">*</span>
                </label>
                <select name="type" id="coupon-type" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                    <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                </select>
                <small style="color:#666;font-size:12px;" id="type-hint">
                    {{ $coupon->type == 'percent' ? 'Giảm theo % giá trị đơn hàng' : 'Giảm số tiền cố định' }}
                </small>
            </div>

            <!-- Giá trị -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Giá trị <span style="color:#e74c3c;">*</span>
                </label>
                <input name="value" type="number" step="0.01" min="0" required value="{{ old('value',$coupon->value) }}" 
                       placeholder="VD: 10 hoặc 50000"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;" id="value-hint">
                    {{ $coupon->type == 'percent' ? 'Nhập số % (0-100)' : 'Nhập số tiền giảm' }}
                </small>
                @error('value')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giảm tối đa -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Giảm tối đa (đ)
                </label>
                <input name="max_discount" type="number" step="0.01" min="0" value="{{ old('max_discount',$coupon->max_discount) }}" 
                       placeholder="VD: 50000"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Chỉ áp dụng với loại "Phần trăm"</small>
                @error('max_discount')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Đơn hàng tối thiểu -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Đơn hàng tối thiểu (đ)
                </label>
                <input name="min_order" type="number" step="0.01" min="0" value="{{ old('min_order',$coupon->min_order) }}" 
                       placeholder="VD: 500000"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Đơn hàng phải từ số tiền này mới áp dụng được</small>
                @error('min_order')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giới hạn lượt dùng -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Giới hạn lượt dùng
                </label>
                <input name="usage_limit" type="number" min="1" value="{{ old('usage_limit',$coupon->usage_limit) }}" 
                       placeholder="VD: 100"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">
                    Đã dùng: <strong>{{ $coupon->used_count }}</strong> lượt
                    @if($coupon->usage_limit)
                        / {{ $coupon->usage_limit }} lượt
                    @endif
                </small>
                @error('usage_limit')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Bắt đầu -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Ngày bắt đầu
                </label>
                <input name="start_at" type="datetime-local" value="{{ old('start_at', $coupon->start_at? $coupon->start_at->format('Y-m-d\TH:i') : '') }}" 
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Để trống = có hiệu lực ngay</small>
                @error('start_at')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kết thúc -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Ngày kết thúc
                </label>
                <input name="end_at" type="datetime-local" value="{{ old('end_at', $coupon->end_at? $coupon->end_at->format('Y-m-d\TH:i') : '') }}" 
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Để trống = không hết hạn</small>
                @error('end_at')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Hoạt động -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Trạng thái
                </label>
                <div style="display:flex;align-items:center;gap:10px;padding:10px;background:#f5f5f5;border-radius:6px;">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $coupon->is_active)?'checked':'' }}>
                    <label for="is_active" style="margin:0;cursor:pointer;">Hoạt động (Bật/Tắt mã giảm giá)</label>
                </div>
            </div>
        </div>

        <div style="margin-top:24px;padding-top:20px;border-top:2px solid #eee;">
            <button class="btn-primary" type="submit" style="padding:12px 24px;">
                <i class="fas fa-save"></i> Cập nhật mã giảm giá
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('coupon-type')?.addEventListener('change', function() {
    const type = this.value;
    const valueHint = document.getElementById('value-hint');
    const typeHint = document.getElementById('type-hint');
    
    if (type === 'percent') {
        valueHint.textContent = 'Nhập số % (0-100). VD: 10 = giảm 10%';
        typeHint.textContent = 'Giảm theo % giá trị đơn hàng';
    } else {
        valueHint.textContent = 'Nhập số tiền giảm. VD: 50000 = giảm 50.000đ';
        typeHint.textContent = 'Giảm số tiền cố định';
    }
});

// Auto uppercase code
document.querySelector('input[name="code"]')?.addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/\s/g, '');
});
</script>
@endpush
@endsection
