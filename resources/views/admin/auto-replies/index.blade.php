@extends('layouts.admin')

@section('title', 'Chat tự động')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-robot"></i> Chat tự động</h1>
    <a href="{{ route('admin.auto-replies.create') }}" class="btn-primary">
        <i class="fas fa-plus"></i> Thêm mới
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <div style="margin-bottom:20px;padding:15px;background:#e3f2fd;border-radius:6px;">
        <i class="fas fa-info-circle" style="color:#1976d2;"></i>
        <strong>Hướng dẫn:</strong> Khi khách hàng gửi tin nhắn chứa từ khóa, hệ thống sẽ tự động trả lời. 
        Từ khóa có thể nhập nhiều, phân tách bởi dấu phẩy. Ví dụ: <code>giá, bao nhiêu tiền, giá bao nhiêu</code>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:30%;">Từ khóa</th>
                <th style="width:40%;">Nội dung trả lời</th>
                <th style="text-align:center;width:10%;">Độ ưu tiên</th>
                <th style="text-align:center;width:10%;">Trạng thái</th>
                <th style="text-align:center;width:10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($autoReplies as $item)
            <tr>
                <td>
                    @foreach(explode(',', $item->keywords) as $keyword)
                        <span style="display:inline-block;background:#e0e0e0;padding:2px 8px;border-radius:12px;font-size:12px;margin:2px;">{{ trim($keyword) }}</span>
                    @endforeach
                </td>
                <td>
                    <div style="max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $item->response }}">
                        {{ \Illuminate\Support\Str::limit($item->response, 100) }}
                    </div>
                </td>
                <td style="text-align:center;">
                    <span style="background:#3498db;color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;">{{ $item->priority }}</span>
                </td>
                <td style="text-align:center;">
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" 
                               data-url="{{ route('admin.auto-replies.toggle', $item) }}"
                               {{ $item->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td style="text-align:center;">
                    <a href="{{ route('admin.auto-replies.edit', $item) }}" class="btn-edit" style="margin-right:5px;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.auto-replies.destroy', $item) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xác nhận xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:#999;">
                    Chưa có câu trả lời tự động nào. 
                    <a href="{{ route('admin.auto-replies.create') }}">Thêm mới ngay</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($autoReplies->hasPages())
    <div class="pagination">
        {{ $autoReplies->links() }}
    </div>
    @endif
</div>
@endsection

