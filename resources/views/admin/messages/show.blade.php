@extends('layouts.admin')

@section('title', 'Chi tiết tin nhắn')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-comment-dots"></i> Chi tiết tin nhắn</h1>
    <a href="{{ route('admin.messages.index') }}" class="btn-primary">← Quay lại danh sách</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<style>
    .message-grid { display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:30px; }
    .message-card { background:white;padding:25px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1); }
    .message-card h2 { margin-bottom:20px;color:#2c3e50;font-size:20px; }
    .message-info { margin-bottom:16px; }
    .message-info strong { display:block;margin-bottom:4px;color:#7f8c8d;font-size:13px; }
    .message-content { margin-top:20px;padding:16px;background:#f5f5f5;border-radius:6px;white-space:pre-wrap;line-height:1.6; }
    .reply-form textarea { width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:14px;resize:vertical;margin-bottom:16px; }
</style>

<div class="message-grid">
    <!-- Tin nhắn khách hàng -->
    <div class="message-card">
        <h2><i class="fas fa-user"></i> Tin nhắn từ khách hàng</h2>
        
        <div class="message-info">
            <strong>Họ và tên:</strong>
            <div>{{ $message->name }}</div>
        </div>
        
        <div class="message-info">
            <strong>Email:</strong>
            <div>{{ $message->email }}</div>
        </div>
        
        @if($message->phone)
        <div class="message-info">
            <strong>Số điện thoại:</strong>
            <div>{{ $message->phone }}</div>
        </div>
        @endif

        @if($message->user)
        <div class="message-info">
            <strong>Thành viên:</strong>
            <div>{{ $message->user->name }} (ID: {{ $message->user->id }})</div>
        </div>
        @endif

        <div class="message-info">
            <strong>Thời gian:</strong>
            <div>{{ $message->created_at->format('d/m/Y H:i:s') }}</div>
        </div>

        <div class="message-info">
            <strong>Trạng thái:</strong>
            <div>
                @if($message->status === 'new')
                    <span class="badge" style="background:#ff9800;color:#fff;">Mới</span>
                @elseif($message->status === 'read')
                    <span class="badge" style="background:#2196f3;color:#fff;">Đã đọc</span>
                @else
                    <span class="badge badge-success">Đã phản hồi</span>
                @endif
            </div>
        </div>

        <div class="message-content">
            <strong>Nội dung tin nhắn:</strong>
            <div style="margin-top:8px;">{{ $message->message }}</div>
        </div>
    </div>

    <!-- Phản hồi admin -->
    <div class="message-card">
        <h2><i class="fas fa-reply"></i> Phản hồi</h2>

        @if($message->admin_reply)
            <div style="margin-bottom:16px;padding:16px;background:#e8f5e9;border-radius:6px;">
                <div style="margin-bottom:8px;">
                    <strong>Phản hồi bởi:</strong> {{ $message->repliedBy->name ?? 'N/A' }}
                </div>
                <div style="margin-bottom:8px;">
                    <strong>Thời gian:</strong> {{ $message->replied_at->format('d/m/Y H:i:s') }}
                </div>
                <div style="white-space:pre-wrap;line-height:1.6;">{{ $message->admin_reply }}</div>
            </div>
        @endif

        <form action="{{ route('admin.messages.reply', $message) }}" method="POST" class="reply-form">
            @csrf
            <label style="display:block;font-weight:600;margin-bottom:8px;">Nội dung phản hồi *</label>
            <textarea name="admin_reply" rows="8" required>{{ old('admin_reply', $message->admin_reply) }}</textarea>
            @error('admin_reply')
            <div style="color:#dc2626;font-size:13px;margin-bottom:16px;">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn-primary">
                {{ $message->admin_reply ? 'Cập nhật phản hồi' : 'Gửi phản hồi' }}
            </button>
        </form>
    </div>
</div>
@endsection

