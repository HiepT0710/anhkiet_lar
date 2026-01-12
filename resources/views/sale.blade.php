@extends('layouts.app')

@section('title', 'Khuyến mãi - Flash Sale - AnhKiet Store')

@push('styles')
<style>
    .sale-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        color: white;
        padding: 40px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
    }
    .sale-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .sale-header p {
        font-size: 16px;
        opacity: 0.95;
    }
    
    .sale-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 2px solid #eee;
    }
    .sale-tab {
        padding: 12px 24px;
        font-size: 15px;
        font-weight: 600;
        color: #666;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        top: 2px;
    }
    .sale-tab:hover {
        color: #d32f2f;
    }
    .sale-tab.active {
        color: #d32f2f;
        border-bottom-color: #d32f2f;
    }
    .sale-tab-badge {
        display: inline-block;
        background: #d32f2f;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 6px;
        font-weight: 700;
    }
    
    .sale-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 25px;
    }
    .sale-sidebar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 20px;
        height: fit-content;
        position: sticky;
        top: 100px;
    }
    .sale-sidebar h3 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }
    .filter-group {
        margin-bottom: 20px;
    }
    .filter-group h4 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #555;
    }
    .filter-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .filter-row input, .filter-row select {
        width: 100%;
        padding: 8px 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 13px;
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        margin-top: 15px;
    }
    .btn-filter {
        flex: 1;
        padding: 10px;
        border-radius: 4px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-filter-primary {
        background: #d32f2f;
        color: #fff;
    }
    .btn-filter-primary:hover {
        background: #b71c1c;
    }
    .btn-filter-secondary {
        background: #f5f5f5;
        color: #333;
    }
    .btn-filter-secondary:hover {
        background: #e0e0e0;
    }
    
    .sale-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    .sale-toolbar p {
        color: #666;
        font-size: 14px;
    }
    .sort-select {
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
        font-size: 14px;
        min-width: 200px;
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
        position: relative;
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
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.4);
    }
    .product-countdown {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 8px;
        border-radius: 6px;
        font-size: 11px;
        display: flex;
        justify-content: space-around;
        gap: 4px;
    }
    .countdown-item {
        text-align: center;
    }
    .countdown-value {
        font-weight: 700;
        font-size: 14px;
        display: block;
    }
    .countdown-label {
        font-size: 9px;
        opacity: 0.8;
        text-transform: uppercase;
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
        flex-wrap: wrap;
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
    .discount-badge {
        background: #ffebee;
        color: #d32f2f;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    .pagination {
        margin-top: 40px;
        text-align: center;
    }
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    .empty-state h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .sale-layout {
            grid-template-columns: 1fr;
        }
        .sale-sidebar {
            position: static;
        }
        .sale-tabs {
            overflow-x: auto;
            scrollbar-width: none;
        }
        .sale-tabs::-webkit-scrollbar {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container" style="padding: 30px 0;">
    <div class="sale-header">
        <h1><i class="fas fa-fire"></i> Flash Sale - Khuyến mãi siêu hot</h1>
        <p>Nhiều sản phẩm đang được giảm giá với mức giá tốt nhất</p>
    </div>

    <!-- Tabs -->
    <div class="sale-tabs">
        <a href="{{ route('sale.index', ['tab' => 'active'] + request()->except('tab')) }}" 
           class="sale-tab {{ $tab === 'active' ? 'active' : '' }}">
            Đang diễn ra
            @if($stats['active_count'] > 0)
                <span class="sale-tab-badge">{{ $stats['active_count'] }}</span>
            @endif
        </a>
        <a href="{{ route('sale.index', ['tab' => 'upcoming'] + request()->except('tab')) }}" 
           class="sale-tab {{ $tab === 'upcoming' ? 'active' : '' }}">
            Sắp diễn ra
            @if($stats['upcoming_count'] > 0)
                <span class="sale-tab-badge">{{ $stats['upcoming_count'] }}</span>
            @endif
        </a>
        <a href="{{ route('sale.index', ['tab' => 'ended'] + request()->except('tab')) }}" 
           class="sale-tab {{ $tab === 'ended' ? 'active' : '' }}">
            Đã kết thúc
            @if($stats['ended_count'] > 0)
                <span class="sale-tab-badge">{{ $stats['ended_count'] }}</span>
            @endif
        </a>
    </div>

    <div class="sale-layout">
        <!-- Sidebar Filter -->
        <aside class="sale-sidebar">
            <h3><i class="fas fa-filter"></i> Bộ lọc</h3>
            <form method="GET">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="filter-group">
                    <h4>Danh mục</h4>
                    <select name="category" class="filter-row">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <h4>Khoảng giá (VNĐ)</h4>
                    <div class="filter-row">
                        <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}" step="1000">
                    </div>
                    <div class="filter-row" style="margin-top: 8px;">
                        <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}" step="1000">
                    </div>
                </div>

                <div class="filter-group">
                    <h4>% Giảm giá tối thiểu</h4>
                    <div class="filter-row">
                        <input type="number" name="min_discount" placeholder="Ví dụ: 10" value="{{ request('min_discount') }}" min="0" max="100">
                    </div>
                </div>

                <input type="hidden" name="sort" value="{{ request('sort', 'ending_soon') }}">
                
                <div class="filter-actions">
                    <button class="btn-filter btn-filter-primary" type="submit">
                        <i class="fas fa-check"></i> Áp dụng
                    </button>
                    <a href="{{ route('sale.index', ['tab' => $tab]) }}" class="btn-filter btn-filter-secondary" style="text-align:center;line-height:38px;text-decoration:none;">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </aside>

        <!-- Main Content -->
        <main>
            <div class="sale-toolbar">
                <p>Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm</p>
                <select name="sort" class="sort-select" onchange="this.form.submit()" form="sort-form">
                    <option value="ending_soon" {{ request('sort') === 'ending_soon' ? 'selected' : '' }}>Sắp kết thúc</option>
                    <option value="discount_desc" {{ request('sort') === 'discount_desc' ? 'selected' : '' }}>Giảm nhiều nhất</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                </select>
                <form id="sort-form" method="GET" style="display:none;">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    @foreach(request()->except(['sort', 'tab', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>

            <div class="products-grid">
                @forelse($products as $product)
                    @php
                        $productImages = $product->images;
                        if (is_string($productImages)) {
                            $productImages = json_decode($productImages, true);
                        }
                        $firstImage = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                        $discountPercent = $product->discount_percentage;
                    @endphp
                    <a href="{{ route('product.show', $product->slug) }}" class="product-card">
                        <div class="product-image">
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}">
                            @else
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+" alt="{{ $product->name }}">
                            @endif
                            <div class="product-badge">
                                -{{ $discountPercent }}%
                            </div>
                            @if($product->sale_ends_at && $tab === 'active')
                                <div class="product-countdown" data-end-time="{{ $product->sale_ends_at->timestamp }}">
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-days>00</span>
                                        <span class="countdown-label">Ngày</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-hours>00</span>
                                        <span class="countdown-label">Giờ</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-minutes>00</span>
                                        <span class="countdown-label">Phút</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-seconds>00</span>
                                        <span class="countdown-label">Giây</span>
                                    </div>
                                </div>
                            @elseif($product->sale_starts_at && $tab === 'upcoming')
                                <div class="product-countdown" style="background: rgba(33, 150, 243, 0.9);" data-start-time="{{ $product->sale_starts_at->timestamp }}">
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-days>00</span>
                                        <span class="countdown-label">Ngày</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-hours>00</span>
                                        <span class="countdown-label">Giờ</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-minutes>00</span>
                                        <span class="countdown-label">Phút</span>
                                    </div>
                                    <div class="countdown-item">
                                        <span class="countdown-value" data-seconds>00</span>
                                        <span class="countdown-label">Giây</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-title">{{ $product->name }}</div>
                            <div class="product-price">
                                <span class="price-current">{{ number_format($product->final_price, 0, ',', '.') }}đ</span>
                                <span class="price-old">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                <span class="discount-badge">-{{ $discountPercent }}%</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-fire"></i>
                        <h3>Chưa có sản phẩm nào</h3>
                        <p>{{ $tab === 'active' ? 'Hiện tại chưa có chương trình flash sale nào đang diễn ra.' : ($tab === 'upcoming' ? 'Chưa có chương trình flash sale nào sắp diễn ra.' : 'Chưa có chương trình flash sale nào đã kết thúc.') }}</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Countdown timer - tự động cập nhật mỗi giây
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.product-countdown').forEach(function(countdown) {
            const endTime = parseInt(countdown.dataset.endTime) || parseInt(countdown.dataset.startTime);
            if (!endTime || isNaN(endTime)) return;
            
            const updateCountdown = function() {
                const now = Math.floor(Date.now() / 1000);
                let remaining = endTime - now;
                
                if (remaining <= 0) {
                    countdown.innerHTML = '<div style="text-align:center;padding:8px;">Đã kết thúc</div>';
                    return;
                }
                
                const days = Math.floor(remaining / 86400);
                remaining -= days * 86400;
                const hours = Math.floor(remaining / 3600);
                remaining -= hours * 3600;
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                
                const daysEl = countdown.querySelector('[data-days]');
                const hoursEl = countdown.querySelector('[data-hours]');
                const minutesEl = countdown.querySelector('[data-minutes]');
                const secondsEl = countdown.querySelector('[data-seconds]');
                
                if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
                if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
                if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
                if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
            };
            
            // Cập nhật ngay lập tức
            updateCountdown();
            
            // Cập nhật mỗi giây
            setInterval(updateCountdown, 1000);
        });
    });
</script>
@endpush

