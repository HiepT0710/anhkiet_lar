@extends('layouts.app')

@section('title', $category->name . ' - AnhKiet Store')

@push('styles')
<style>
    .category-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
    }
    .category-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    .category-header p {
        color: #666;
        font-size: 14px;
    }
    .category-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
    }
    .category-sidebar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 18px;
        font-size: 14px;
    }
    .filter-group {
        margin-bottom: 18px;
    }
    .filter-group h3 {
        font-size: 15px;
        margin-bottom: 10px;
    }
    .filter-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .filter-row input {
        width: 100%;
        padding: 6px 8px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 13px;
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        margin-top: 10px;
    }
    .btn-filter {
        flex: 1;
        padding: 8px 10px;
        border-radius: 4px;
        border: none;
        font-size: 13px;
        cursor: pointer;
    }
    .btn-filter-primary {
        background: #d32f2f;
        color: #fff;
    }
    .btn-filter-secondary {
        background: #f5f5f5;
        color: #333;
    }
    .sort-select {
        padding: 8px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 13px;
        min-width: 200px;
    }
    .category-meta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        font-size: 13px;
        color: #666;
        margin-top: 4px;
    }
    .price-range-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .price-range-chips a {
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid #eee;
        font-size: 12px;
        color: #333;
        text-decoration: none;
        background: #fafafa;
    }
    .price-range-chips a:hover {
        border-color: #d32f2f;
        color: #d32f2f;
        background: #fff5f5;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }
    .product-card {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        color: #333;
        display: block;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    .product-image {
        width: 100%;
        height: 200px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .product-image img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }
    .product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #d32f2f;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .product-info {
        padding: 15px;
    }
    .product-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .product-price {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    .price-current {
        font-size: 18px;
        font-weight: 700;
        color: #d32f2f;
    }
    .price-old {
        font-size: 14px;
        color: #999;
        text-decoration: line-through;
    }
    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 10px;
    }
    .product-rating i {
        color: #ffc107;
        font-size: 12px;
    }
    .product-rating span {
        font-size: 12px;
        color: #999;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="category-header">
        <h1>{{ $category->name }}</h1>
        <div>
            @if($category->description)
            <p>{{ $category->description }}</p>
            @endif
            <form method="GET">
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="">Sắp xếp: Mặc định</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Bán chạy / nổi bật</option>
                </select>
            </form>
            <div class="category-meta">
                <span>{{ $products->total() }} sản phẩm</span>
            </div>
        </div>
    </div>

    <div class="category-layout">
        <aside class="category-sidebar">
            <form method="GET">
                <div class="filter-group">
                    <h3>Khoảng giá (đ)</h3>
                    <div class="filter-row">
                        <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}">
                    </div>
                    <div class="price-range-chips">
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 0, 'max_price' => 2000000])) }}">Dưới 2 triệu</a>
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 2000000, 'max_price' => 4000000])) }}">2 - 4 triệu</a>
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 4000000, 'max_price' => 7000000])) }}">4 - 7 triệu</a>
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 7000000, 'max_price' => 13000000])) }}">7 - 13 triệu</a>
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 13000000, 'max_price' => 20000000])) }}">13 - 20 triệu</a>
                        <a href="{{ route('category.show', array_merge(['slug' => $category->slug], request()->except(['page', 'min_price', 'max_price']) + ['min_price' => 20000000])) }}">Trên 20 triệu</a>
                    </div>
                </div>
                <div class="filter-group">
                    <h3>Khuyến mãi</h3>
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;">
                        <input type="checkbox" name="on_sale" value="1" {{ request('on_sale') === '1' ? 'checked' : '' }}>
                        Chỉ hiển thị sản phẩm đang giảm giá / flash sale
                    </label>
                </div>
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <div class="filter-actions">
                    <button class="btn-filter btn-filter-primary" type="submit">Áp dụng</button>
                    <a href="{{ route('category.show', $category->slug) }}" class="btn-filter btn-filter-secondary" style="text-align:center;line-height:26px;">Xóa lọc</a>
                </div>
            </form>
        </aside>

        <div>
            <div class="products-grid">
                @forelse($products as $product)
                <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                    <div class="product-image">
                        @php
                            $productImages = $product->images;
                            if (is_string($productImages)) {
                                $productImages = json_decode($productImages, true);
                            }
                            $firstImage = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                        @endphp
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}">
                        @else
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+" alt="{{ $product->name }}">
                        @endif
                        @if($product->is_sale_active)
                        <div class="product-badge">-{{ $product->discount_percentage }}%</div>
                        @endif
                    </div>
                    <div class="product-info">
                        <div class="product-title">{{ $product->name }}</div>
                        <div class="product-price">
                            <span class="price-current">{{ number_format($product->final_price) }}đ</span>
                            @if($product->is_sale_active)
                            <span class="price-old">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span>(125)</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="no-products">
                    <p>Chưa có sản phẩm nào trong danh mục này.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div style="margin-top: 40px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

