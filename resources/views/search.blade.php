@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@push('styles')
<style>
    .search-header {
        margin-bottom: 20px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .search-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    .search-header p {
        color: #666;
        font-size: 14px;
    }
    
    .search-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 25px;
    }
    .search-sidebar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
    }
    .search-sidebar h3 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
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
    .search-history-list,
    .search-trend-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .search-history-list li,
    .search-trend-list li {
        margin-bottom: 8px;
    }
    .search-history-list a,
    .search-trend-list a {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        color: #333;
        font-size: 14px;
        padding: 6px 8px;
        border-radius: 4px;
        transition: background 0.2s;
    }
    .search-history-list a:hover,
    .search-trend-list a:hover {
        background: #f5f5f5;
    }
    .search-history-icon {
        color: #999;
        margin-right: 6px;
    }
    .search-trend-badge {
        font-size: 11px;
        color: #d32f2f;
        font-weight: 600;
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
    .pagination {
        margin-top: 30px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container" style="padding: 30px 0;">
    <div class="search-header">
        <h1>Kết quả tìm kiếm</h1>
        @if(request('q'))
            <p>Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm cho từ khóa "<strong>{{ request('q') }}</strong>"</p>
        @else
            <p>Vui lòng nhập từ khóa tìm kiếm</p>
        @endif
    </div>

    <div class="search-layout">
        <aside class="search-sidebar">
            <form method="GET">
                <input type="hidden" name="q" value="{{ request('q') }}">
                <div class="filter-group">
                    <h3>Khoảng giá (đ)</h3>
                    <div class="filter-row">
                        <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}">
                        <span>-</span>
                        <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}">
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
                    <a href="{{ route('search', ['q' => request('q')]) }}" class="btn-filter btn-filter-secondary" style="text-align:center;line-height:26px;">Xóa lọc</a>
                </div>
            </form>

            @if(isset($recentSearches) && $recentSearches->count())
                <div style="margin-top: 24px;">
                    <h3><i class="fas fa-clock search-history-icon"></i> Lịch sử tìm kiếm</h3>
                    <ul class="search-history-list">
                        @foreach($recentSearches as $history)
                            <li>
                                <a href="{{ route('search', ['q' => $history->keyword]) }}">
                                    <span><i class="fas fa-search search-history-icon"></i>{{ $history->keyword }}</span>
                                    <span style="font-size: 11px; color:#999;">{{ $history->search_count }} lần</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($suggestedProducts) && $suggestedProducts->count())
                <div style="margin-top: 20px;">
                    <h3>Xu hướng tìm kiếm <span>🔥</span></h3>
                    <ul class="search-trend-list">
                        @foreach($suggestedProducts as $product)
                            <li>
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <span>{{ $product->name }}</span>
                                    @if($product->is_flash_sale || $product->featured)
                                        <span class="search-trend-badge">
                                            @if($product->is_flash_sale)
                                                FLASH SALE
                                            @elseif($product->featured)
                                                HOT
                                            @endif
                                        </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>

        <div>
            <div style="display:flex;justify-content:flex-end;margin-bottom:10px;">
                <form method="GET">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    <input type="hidden" name="on_sale" value="{{ request('on_sale') }}">
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="">Sắp xếp: Mặc định</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Bán chạy / nổi bật</option>
                    </select>
                </form>
            </div>
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
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
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Không tìm thấy sản phẩm nào</h3>
                    <p style="color: #999;">Hãy thử tìm kiếm với từ khóa khác</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

