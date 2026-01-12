@extends('layouts.admin')

@section('title', 'Thêm chat tự động')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-robot"></i> Thêm câu trả lời tự động</h1>
    <a href="{{ route('admin.auto-replies.index') }}" class="btn-primary">← Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('admin.auto-replies.store') }}" method="POST">
        @csrf
        
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">
                Từ khóa kích hoạt <span style="color:#e74c3c;">*</span>
            </label>
            <input type="text" name="keywords" value="{{ old('keywords') }}" 
                   style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;"
                   placeholder="Nhập từ khóa, phân tách bởi dấu phẩy. VD: giá, bao nhiêu tiền, giá bao nhiêu">
            <small style="color:#666;display:block;margin-top:4px;">
                Khi khách hàng gửi tin nhắn chứa bất kỳ từ khóa nào, hệ thống sẽ tự động trả lời.
            </small>
            @error('keywords')
            <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">
                Nội dung trả lời <span style="color:#e74c3c;">*</span>
            </label>
            <textarea name="response" rows="6"
                      style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;resize:vertical;"
                      placeholder="Nhập nội dung trả lời tự động...">{{ old('response') }}</textarea>
            @error('response')
            <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">
                Độ ưu tiên
            </label>
            <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" max="100"
                   style="width:150px;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;">
            <small style="color:#666;display:block;margin-top:4px;">
                Số lớn hơn sẽ được ưu tiên trả lời trước (0-100).
            </small>
            @error('priority')
            <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="padding-top:20px;border-top:1px solid #eee;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Lưu
            </button>
        </div>
    </form>
</div>

<div class="table-section" style="margin-top:20px;">
    <h3 style="margin-bottom:15px;color:#2c3e50;">💡 Gợi ý từ khóa phổ biến</h3>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        @php
        $suggestions = [
            ['keywords' => 'giá, bao nhiêu tiền, giá bao nhiêu', 'desc' => 'Hỏi về giá'],
            ['keywords' => 'giao hàng, ship, vận chuyển', 'desc' => 'Hỏi về giao hàng'],
            ['keywords' => 'bảo hành, warranty', 'desc' => 'Hỏi về bảo hành'],
            ['keywords' => 'trả góp, góp, installment', 'desc' => 'Hỏi về trả góp'],
            ['keywords' => 'đổi trả, hoàn tiền, return', 'desc' => 'Hỏi về đổi trả'],
            ['keywords' => 'địa chỉ, cửa hàng, showroom', 'desc' => 'Hỏi về địa chỉ'],
            ['keywords' => 'liên hệ, hotline, số điện thoại', 'desc' => 'Hỏi liên hệ'],
            ['keywords' => 'khuyến mãi, giảm giá, sale, voucher', 'desc' => 'Hỏi khuyến mãi'],
            ['keywords' => 'còn hàng, hết hàng, stock', 'desc' => 'Hỏi tồn kho'],
            ['keywords' => 'xin chào, hello, hi', 'desc' => 'Lời chào'],
        ];
        @endphp
        @foreach($suggestions as $s)
        <div style="background:#f5f5f5;padding:8px 12px;border-radius:6px;font-size:13px;">
            <strong>{{ $s['desc'] }}:</strong> <code>{{ $s['keywords'] }}</code>
        </div>
        @endforeach
    </div>
</div>
@endsection

