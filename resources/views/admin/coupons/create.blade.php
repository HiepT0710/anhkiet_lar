@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-ticket-alt"></i> Thêm mã giảm giá</h1>
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

<!-- Hướng dẫn -->
<div class="table-section" style="margin-bottom:20px;background:#e3f2fd;">
    <h3 style="margin-bottom:12px;color:#1976d2;"><i class="fas fa-info-circle"></i> Hướng dẫn tạo mã giảm giá</h3>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
        <div>
            <strong>📝 Ví dụ mã giảm giá:</strong>
            <ul style="margin:8px 0 0 20px;font-size:14px;">
                <li><strong>SALE10</strong> - Giảm 10% (tối đa 50.000đ)</li>
                <li><strong>GIAM50K</strong> - Giảm 50.000đ cố định</li>
                <li><strong>FREESHIP</strong> - Miễn phí ship (giảm 30.000đ)</li>
            </ul>
        </div>
        <div>
            <strong>💡 Lưu ý:</strong>
            <ul style="margin:8px 0 0 20px;font-size:14px;">
                <li>Code phải là chữ in hoa, không dấu cách</li>
                <li>Phần trăm: Nhập số từ 0-100 (ví dụ: 10 = 10%)</li>
                <li>Cố định: Nhập số tiền giảm (ví dụ: 50000 = 50.000đ)</li>
            </ul>
        </div>
    </div>
</div>

<div class="table-section">
    <form action="{{ route('coupons.store') }}" method="POST">
        @csrf
        
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
            <!-- Code -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Mã giảm giá <span style="color:#e74c3c;">*</span>
                </label>
                <input name="code" required value="{{ old('code') }}" 
                       placeholder="VD: SALE10, GIAM50K"
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
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                </select>
                <small style="color:#666;font-size:12px;" id="type-hint">Giảm theo % giá trị đơn hàng</small>
            </div>

            <!-- Giá trị -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Giá trị <span style="color:#e74c3c;">*</span>
                </label>
                <input name="value" type="number" step="0.01" min="0" required value="{{ old('value',0) }}" 
                       placeholder="VD: 10 hoặc 50000"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;" id="value-hint">Nhập số % hoặc số tiền</small>
                @error('value')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giảm tối đa -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Giảm tối đa (đ)
                </label>
                <input name="max_discount" type="number" step="0.01" min="0" value="{{ old('max_discount') }}" 
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
                <input name="min_order" type="number" step="0.01" min="0" value="{{ old('min_order') }}" 
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
                <input name="usage_limit" type="number" min="1" value="{{ old('usage_limit') }}" 
                       placeholder="VD: 100"
                       style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;">
                <small style="color:#666;font-size:12px;">Để trống = không giới hạn</small>
                @error('usage_limit')
                <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Bắt đầu -->
            <div>
                <label style="display:block;font-weight:600;margin-bottom:6px;">
                    Ngày bắt đầu
                </label>
                <input name="start_at" type="datetime-local" value="{{ old('start_at') }}" 
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
                <input name="end_at" type="datetime-local" value="{{ old('end_at') }}" 
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
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true)?'checked':'' }}>
                    <label for="is_active" style="margin:0;cursor:pointer;">Hoạt động (Bật/Tắt mã giảm giá)</label>
                </div>
            </div>
        </div>

        <div style="margin-top:24px;padding-top:20px;border-top:2px solid #eee;">
            <button class="btn-primary" type="submit" style="padding:12px 24px;">
                <i class="fas fa-save"></i> Lưu mã giảm giá
            </button>
        </div>
    </form>
</div>

<!-- Ví dụ cụ thể -->
<div class="table-section" style="margin-top:20px;">
    <h3 style="margin-bottom:16px;color:#2c3e50;"><i class="fas fa-lightbulb"></i> Ví dụ cụ thể</h3>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        <div style="padding:16px;background:#e8f5e9;border-radius:8px;">
            <strong style="color:#2e7d32;">Ví dụ 1: Giảm 10%</strong>
            <ul style="margin:8px 0 0 20px;font-size:13px;color:#666;">
                <li>Code: <code>SALE10</code></li>
                <li>Kiểu: Phần trăm</li>
                <li>Giá trị: <code>10</code></li>
                <li>Giảm tối đa: <code>50000</code></li>
                <li><strong>→ Đơn 1.000.000đ giảm 50.000đ (tối đa)</strong></li>
            </ul>
        </div>
        <div style="padding:16px;background:#fff3e0;border-radius:8px;">
            <strong style="color:#e65100;">Ví dụ 2: Giảm 50.000đ</strong>
            <ul style="margin:8px 0 0 20px;font-size:13px;color:#666;">
                <li>Code: <code>GIAM50K</code></li>
                <li>Kiểu: Cố định</li>
                <li>Giá trị: <code>50000</code></li>
                <li>ĐH tối thiểu: <code>500000</code></li>
                <li><strong>→ Đơn từ 500.000đ giảm 50.000đ</strong></li>
            </ul>
        </div>
        <div style="padding:16px;background:#e3f2fd;border-radius:8px;">
            <strong style="color:#1976d2;">Ví dụ 3: Giảm 20% không giới hạn</strong>
            <ul style="margin:8px 0 0 20px;font-size:13px;color:#666;">
                <li>Code: <code>SALE20</code></li>
                <li>Kiểu: Phần trăm</li>
                <li>Giá trị: <code>20</code></li>
                <li>Giảm tối đa: <strong>Để trống</strong></li>
                <li><strong>→ Đơn 1.000.000đ giảm 200.000đ</strong></li>
            </ul>
        </div>
    </div>
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


