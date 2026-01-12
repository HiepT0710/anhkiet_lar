@extends('layouts.admin')

@section('title', 'Quản lý tin nhắn')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-comments"></i> Quản lý tin nhắn</h1>
    <div>
        <span style="background:#ff9800;color:#fff;padding:6px 12px;border-radius:4px;font-size:14px;font-weight:600;">
            Tin nhắn mới: {{ \App\Models\Message::where('status', 'new')->count() }}
        </span>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
            <tr>
                <th>Người gửi</th>
                <th>Email</th>
                <th>Tin nhắn</th>
                <th style="text-align:center;">Trạng thái</th>
                <th style="text-align:center;">Thời gian</th>
                <th style="text-align:center;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $msg)
            <tr style="{{ $msg->status === 'new' ? 'background:#fff9e6;' : '' }}">
                <td>
                    <strong>{{ $msg->name }}</strong>
                    @if($msg->user)
                        <div style="font-size:12px;color:#666;">(Thành viên)</div>
                    @endif
                    @if($msg->phone)
                        <div style="font-size:12px;color:#666;">{{ $msg->phone }}</div>
                    @endif
                </td>
                <td>{{ $msg->email }}</td>
                <td>
                    <div style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $msg->message }}">
                        {{ \Illuminate\Support\Str::limit($msg->message, 80) }}
                    </div>
                </td>
                <td style="text-align:center;">
                    @if($msg->status === 'new')
                        <span class="badge" style="background:#ff9800;color:#fff;">Mới</span>
                    @elseif($msg->status === 'read')
                        <span class="badge" style="background:#2196f3;color:#fff;">Đã đọc</span>
                    @else
                        <span class="badge badge-success">Đã phản hồi</span>
                    @endif
                </td>
                <td style="text-align:center;font-size:13px;color:#666;">
                    {{ $msg->created_at->format('d/m/Y H:i') }}
                </td>
                <td style="text-align:center;">
                    <a href="{{ route('admin.messages.show', $msg) }}" class="btn-primary" style="padding:6px 12px;font-size:13px;">
                        Xem chi tiết
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:#999;">Chưa có tin nhắn nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($messages->hasPages())
    <div style="padding:20px;text-align:center;">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection

