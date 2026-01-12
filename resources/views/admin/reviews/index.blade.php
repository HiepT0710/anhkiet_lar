@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-star"></i> Quản lý đánh giá</h1>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Thống kê -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: #e3f2fd; color: #1976d2; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-comments"></i>
            </div>
            <div>
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Tổng đánh giá</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($totalReviews) }}</div>
            </div>
        </div>
    </div>
    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: #e8f5e9; color: #388e3c; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Đã duyệt</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($approvedReviews) }}</div>
            </div>
        </div>
    </div>
    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: #fff3e0; color: #f57c00; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div style="color: #7f8c8d; font-size: 14px; margin-bottom: 5px;">Chờ duyệt</div>
                <div style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($pendingReviews) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Form tìm kiếm và lọc -->
<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('admin.reviews.index') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label for="search" style="display: block; margin-bottom: 5px; font-weight: 600;">Tìm kiếm</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Tên, nội dung, sản phẩm..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="width: 200px;">
            <label for="status" style="display: block; margin-bottom: 5px; font-weight: 600;">Trạng thái</label>
            <select id="status" name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Tất cả</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
        @if(request('search') || request('status'))
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="btn-primary" style="background: #95a5a6; padding: 10px 20px;">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Bulk Actions -->
@if($reviews->count() > 0)
<form id="bulkForm" method="POST" action="" style="margin-bottom: 15px;">
    @csrf
    <div style="display: flex; gap: 10px; align-items: center;">
        <button type="button" onclick="selectAll()" class="btn-primary" style="background: #6c757d; padding: 8px 15px; font-size: 13px;">
            <i class="fas fa-check-square"></i> Chọn tất cả
        </button>
        <button type="button" onclick="bulkApprove()" class="btn-primary" style="background: #28a745; padding: 8px 15px; font-size: 13px;">
            <i class="fas fa-check"></i> Duyệt đã chọn
        </button>
        <button type="button" onclick="bulkDelete()" class="btn-primary" style="background: #dc3545; padding: 8px 15px; font-size: 13px;">
            <i class="fas fa-trash"></i> Xóa đã chọn
        </button>
    </div>
</form>
@endif

<!-- Bảng danh sách đánh giá -->
<div class="table-section">
    <table>
        <thead>
            <tr>
                <th style="width: 40px;"><input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()"></th>
                <th>ID</th>
                <th>Người đánh giá</th>
                <th>Sản phẩm</th>
                <th>Đánh giá</th>
                <th>Nội dung</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>
                    <input type="checkbox" name="review_ids[]" value="{{ $review->id }}" class="review-checkbox">
                </td>
                <td>{{ $review->id }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $review->name ?? $review->user->name ?? 'Khách hàng' }}</div>
                    @if($review->email)
                    <div style="font-size: 12px; color: #999;">{{ $review->email }}</div>
                    @endif
                </td>
                <td>
                    @if($review->product)
                    <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" style="color: #2563eb; text-decoration: none;">
                        {{ Str::limit($review->product->name, 40) }}
                    </a>
                    @else
                    <span style="color: #999;">Đã xóa</span>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 3px; align-items: center;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#ffc107' : '#ddd' }}; font-size: 16px;"></i>
                        @endfor
                        <span style="margin-left: 5px; font-weight: 600;">{{ $review->rating }}/5</span>
                    </div>
                </td>
                <td>
                    @if($review->comment)
                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $review->comment }}">
                        {{ Str::limit($review->comment, 50) }}
                    </div>
                    @else
                    <span style="color: #999;">Không có nhận xét</span>
                    @endif
                </td>
                <td>
                    @if($review->is_approved)
                    <span style="padding: 5px 12px; border-radius: 20px; background: #e8f5e9; color: #388e3c; font-weight: 600; font-size: 12px;">
                        <i class="fas fa-check-circle"></i> Đã duyệt
                    </span>
                    @else
                    <span style="padding: 5px 12px; border-radius: 20px; background: #fff3e0; color: #f57c00; font-weight: 600; font-size: 12px;">
                        <i class="fas fa-clock"></i> Chờ duyệt
                    </span>
                    @endif
                </td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        @if(!$review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-edit" style="padding: 5px 10px; font-size: 12px;" title="Duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-edit" style="padding: 5px 10px; font-size: 12px; background: #ff9800;" title="Từ chối">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding: 5px 10px; font-size: 12px;" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding: 40px;">
                    <i class="fas fa-comments" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                    <p style="color: #999;">Chưa có đánh giá nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $reviews->links() }}
    </div>
</div>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAllCheckbox');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(cb => cb.checked = true);
    document.getElementById('selectAllCheckbox').checked = true;
}

function bulkApprove() {
    const form = document.getElementById('bulkForm');
    const checked = document.querySelectorAll('.review-checkbox:checked');
    if (checked.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá!');
        return;
    }
    if (!confirm('Bạn có chắc chắn muốn duyệt ' + checked.length + ' đánh giá đã chọn?')) {
        return;
    }
    form.action = '{{ route("admin.reviews.bulk-approve") }}';
    form.submit();
}

function bulkDelete() {
    const form = document.getElementById('bulkForm');
    const checked = document.querySelectorAll('.review-checkbox:checked');
    if (checked.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá!');
        return;
    }
    if (!confirm('Bạn có chắc chắn muốn xóa ' + checked.length + ' đánh giá đã chọn?')) {
        return;
    }
    form.action = '{{ route("admin.reviews.bulk-delete") }}';
    form.submit();
}
</script>
@endsection

