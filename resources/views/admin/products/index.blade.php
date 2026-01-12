@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-box"></i> Quản lý sản phẩm</h1>
    <a href="{{ route('products.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm sản phẩm</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Form tìm kiếm -->
<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('products.index') }}" style="display: flex; gap: 15px; align-items: flex-end;">
        <div style="flex: 1;">
            <label for="search" style="display: block; margin-bottom: 5px; font-weight: 600;">Tìm kiếm theo tên</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nhập tên sản phẩm..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="width: 250px;">
            <label for="category_id" style="display: block; margin-bottom: 5px; font-weight: 600;">Lọc theo danh mục</label>
            <select id="category_id" name="category_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
        @if(request('search') || request('category_id'))
        <div>
            <a href="{{ route('products.index') }}" class="btn-primary" style="background: #95a5a6; padding: 10px 20px;">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
        @endif
    </form>
</div>

<div class="table-section">
    @if(request('search') || request('category_id'))
        <div style="margin-bottom: 15px; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <strong>Kết quả tìm kiếm:</strong> Tìm thấy {{ $products->total() }} sản phẩm
            @if(request('search'))
                với từ khóa "<strong>{{ request('search') }}</strong>"
            @endif
            @if(request('category_id'))
                @php $selectedCategory = $categories->firstWhere('id', request('category_id')); @endphp
                @if($selectedCategory)
                    trong danh mục "<strong>{{ $selectedCategory->name }}</strong>"
                @endif
            @endif
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Giá sale</th>
                <th>Kho</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @php
                        $productImages = $product->images;
                        if (is_string($productImages)) {
                            $productImages = json_decode($productImages, true);
                        }
                        $firstImage = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    @else
                        <span class="badge badge-danger">Không có ảnh</span>
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ number_format($product->price) }}đ</td>
                <td>{{ $product->sale_price ? number_format($product->sale_price) . 'đ' : '-' }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('products.toggle', $product) }}" {{ $product->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;">Chưa có sản phẩm nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.toggle-status').forEach(function(input) {
        input.addEventListener('change', async function(e) {
            const checkbox = e.currentTarget;
            const url = checkbox.getAttribute('data-url');
            const previous = !checkbox.checked; // keep last state in case of error
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Request failed');
            } catch (err) {
                checkbox.checked = previous; // revert on error
                alert('Không thể cập nhật trạng thái. Vui lòng thử lại.');
            }
        });
    });
});
</script>
@endpush
