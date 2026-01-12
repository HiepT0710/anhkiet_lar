@extends('layouts.admin')

@section('title', 'Sửa chat tự động')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-robot"></i> Sửa câu trả lời tự động</h1>
    <a href="{{ route('admin.auto-replies.index') }}" class="btn-primary">← Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('admin.auto-replies.update', $autoReply) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">
                Từ khóa kích hoạt <span style="color:#e74c3c;">*</span>
            </label>
            <input type="text" name="keywords" value="{{ old('keywords', $autoReply->keywords) }}" 
                   style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;"
                   placeholder="Nhập từ khóa, phân tách bởi dấu phẩy">
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
                      placeholder="Nhập nội dung trả lời tự động...">{{ old('response', $autoReply->response) }}</textarea>
            @error('response')
            <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;margin-bottom:8px;">
                Độ ưu tiên
            </label>
            <input type="number" name="priority" value="{{ old('priority', $autoReply->priority) }}" min="0" max="100"
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
                <i class="fas fa-save"></i> Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection

