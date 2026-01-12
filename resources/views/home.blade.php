@extends('layouts.app')

@section('title', 'Trang chủ - AnhKiet Store')

@push('styles')
<style>
    /* Banner Section */
    .banner-section {
        margin: 20px 0;
    }
    .banner-main {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 20px;
    }
    .banner-sidebar {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
    }
    .banner-sidebar h3 {
        font-size: 16px;
        margin-bottom: 15px;
        color: #d32f2f;
    }
    .banner-sidebar ul {
        list-style: none;
    }
    .banner-sidebar ul li {
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        position: relative;
    }
    .banner-sidebar ul li a {
        color: #333;
        text-decoration: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .banner-sidebar ul li a:hover {
        color: #d32f2f;
    }
    .category-mega {
        position: absolute;
        top: 0;
        left: 100%;
        margin-left: 10px;
        width: 720px;
        min-height: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        padding: 18px 20px;
        display: none;
        z-index: 20;
    }
    .banner-sidebar ul li:hover > .category-mega {
        display: grid;
    }
    .category-mega-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        font-size: 13px;
    }
    .category-mega h4 {
        font-size: 14px;
        margin-bottom: 10px;
        font-weight: 700;
    }
    .category-mega-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .category-mega-list a {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 20px;
        border: 1px solid #eee;
        text-decoration: none;
        color: #333;
        background: #fafafa;
    }
    .category-mega-list a:hover {
        border-color: #d32f2f;
        color: #d32f2f;
        background: #fff5f5;
    }
    .banner-carousel {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        height: 400px;
    }
    .banner-slides {
        display: flex;
        transition: transform 0.5s ease-in-out;
        height: 100%;
    }
    .banner-slide {
        min-width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        position: absolute;
        width: 100%;
    }
    .banner-slide.active {
        opacity: 1;
    }
    .banner-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .banner-prev, .banner-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        z-index: 10;
    }
    .banner-prev {
        left: 8px;
    }
    .banner-next {
        right: 8px;
    }
    .banner-prev:hover, .banner-next:hover {
        background: rgba(0,0,0,0.8);
        transform: translateY(-50%) scale(1.05);
    }
    .banner-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
    }
    .banner-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: none;
        background: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.3s;
    }
    .banner-indicators button.active {
        background: #fff;
        width: 30px;
        border-radius: 6px;
    }

    /* Section Styles */
    .section {
        margin: 40px 0;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .section-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .section-header a {
        color: #d32f2f;
        text-decoration: none;
        font-weight: 500;
    }

    /* Categories Grid */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 20px;
    }
    .category-card {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        text-decoration: none;
        display: block;
        color: #333;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .category-card i {
        font-size: 40px;
        color: #d32f2f;
        margin-bottom: 10px;
    }
    .category-card h3 {
        font-size: 14px;
        font-weight: 600;
        margin-top: 10px;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
    .product-card-inner {
        position: relative;
    }
    .product-wishlist-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
        border-radius: 999px;
        border: none;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #9ca3af;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .product-wishlist-btn.active {
        color: #ef4444;
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
        z-index: 2;
    }
    .product-badge.featured {
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        box-shadow: 0 2px 8px rgba(255, 107, 107, 0.4);
        animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .product-badge.sale {
        background: #d32f2f;
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
        line-clamp: 2;
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

    /* Flash Sale Section */
    .flash-sale-wrapper {
        margin: 32px 0;
        border-radius: 18px;
        overflow: hidden;
        background: linear-gradient(120deg, #ff4d9f 0%, #ff3d6f 40%, #b8007f 100%);
        position: relative;
        color: #fff;
        box-shadow: 0 15px 35px rgba(255, 0, 89, 0.15);
    }
    .flash-sale-banner {
        /* Removed external image to avoid 404 errors */
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
    }
    .flash-sale-label {
        background: linear-gradient(135deg, #ffda44, #ff9f0d);
        color: #a30000;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 18px rgba(255, 184, 0, 0.25);
    }
    .flash-sale-heading h2 {
        font-size: 24px;
        letter-spacing: 1px;
        margin: 2px 0 0;
        text-transform: uppercase;
        font-weight: 700;
    }
    .flash-sale-heading p {
        margin: 0;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .flash-sale-cta {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }
    .flash-sale-cta span {
        font-weight: 600;
    }
    .flash-sale-cta a {
        background: #fff;
        color: #ff0054;
        padding: 10px 18px;
        border-radius: 999px;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        font-size: 13px;
    }
    .flash-sale-content {
        padding: 20px 22px 26px;
    }
    .flash-sale-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .flash-sale-tabs::-webkit-scrollbar { display: none; }
    .flash-sale-tab {
        border: 2px solid rgba(255,255,255,0.4);
        border-radius: 999px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .flash-sale-tab.active {
        background: #fff;
        color: #ff0b5d;
        border-color: #fff;
    }
    .flash-sale-countdown {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .flash-sale-countdown .time-box {
        background: rgba(0,0,0,0.15);
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 20px;
        font-weight: 800;
        min-width: 58px;
        text-align: center;
    }
    .flash-sale-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
    }
    .flash-sale-card {
        background: #fff;
        color: #2d2d2d;
        border-radius: 16px;
        padding: 14px;
        position: relative;
        box-shadow: 0 10px 20px rgba(74,0,50,0.12);
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .flash-sale-card-brand {
        font-size: 11px;
        font-weight: 700;
        color: #ff0b5d;
        text-transform: uppercase;
    }
    .flash-sale-card-image {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .flash-sale-card-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .flash-sale-card h4 {
        font-size: 13px;
        font-weight: 700;
        margin: 0;
        min-height: 40px;
    }
    .flash-sale-features {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 12px;
        color: #7a7a7a;
    }
    .flash-sale-features li {
        background: #f6f6f6;
        padding: 4px 8px;
        border-radius: 6px;
    }
    .flash-sale-pricing {
        display: flex;
        align-items: baseline;
        gap: 10px;
    }
    .flash-sale-price {
        font-size: 16px;
        font-weight: 800;
        color: #ff0b5d;
    }
    .flash-sale-price-old {
        color: #bcbcbc;
        text-decoration: line-through;
        font-size: 14px;
    }
    .flash-sale-progress {
        margin-top: auto;
        background: #ffe3ed;
        color: #ff0b5d;
        border-radius: 999px;
        text-align: center;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 600;
    }
    .flash-sale-note {
        text-align: center;
        margin-top: 18px;
        font-weight: 500;
        font-size: 12px;
        opacity: 0.85;
    }
    .flash-sale-empty {
        grid-column: 1 / -1;
        background: rgba(255,255,255,0.15);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        font-weight: 600;
        color: #fff;
    }
    .flash-sale-empty small {
        display: block;
        margin-top: 8px;
        font-weight: 500;
        opacity: 0.85;
    }
    @media (max-width: 992px) {
        .flash-sale-banner {
            background-size: 150px auto;
            padding: 18px;
        }
        .flash-sale-heading h2 {
            font-size: 22px;
        }
        .flash-sale-content {
            padding: 18px;
        }
    }
    @media (max-width: 768px) {
        .flash-sale-banner {
            flex-direction: column;
            align-items: flex-start;
            background: none;
        }
        .flash-sale-cta {
            margin-left: 0;
        }
        .flash-sale-countdown {
            justify-content: flex-start;
        }
        .flash-sale-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }
    }
    @media (max-width: 576px) {
        .flash-sale-wrapper {
            margin: 26px 0;
            border-radius: 14px;
        }
        .flash-sale-label {
            font-size: 12px;
        }
        .flash-sale-heading h2 {
            font-size: 22px;
        }
        .flash-sale-card {
            padding: 12px;
            border-radius: 14px;
        }
        .flash-sale-card-image {
            height: 110px;
        }
        .flash-sale-price {
            font-size: 15px;
        }
        .flash-sale-countdown .time-box {
            font-size: 17px;
            min-width: 48px;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Banner Section -->
    <div class="banner-section">
        <div class="banner-main">
            <div class="banner-sidebar">
                <h3>DANH MỤC SẢN PHẨM</h3>
                <ul>
                    @forelse($sidebarCategories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}">
                            <i class="{{ $category->icon ?? 'fas fa-box' }}"></i> {{ $category->name }}
                        </a>
                        <div class="category-mega">
                            <div class="category-mega-grid">
                                <div>
                                    <h4>Hãng {{ $category->name }}</h4>
                                    <div class="category-mega-list">
                                        @if(isset($brands) && $brands->count())
                                            @foreach($brands as $brand)
                                                <a href="{{ route('search', ['q' => $brand->name, 'category' => $category->slug]) }}">{{ $brand->name }}</a>
                                            @endforeach
                                        @else
                                            <a href="{{ route('search', ['q' => 'iPhone', 'category' => $category->slug]) }}">Apple</a>
                                            <a href="{{ route('search', ['q' => 'Samsung', 'category' => $category->slug]) }}">Samsung</a>
                                            <a href="{{ route('search', ['q' => 'Xiaomi', 'category' => $category->slug]) }}">Xiaomi</a>
                                            <a href="{{ route('search', ['q' => 'OPPO', 'category' => $category->slug]) }}">OPPO</a>
                                            <a href="{{ route('search', ['q' => 'Vivo', 'category' => $category->slug]) }}">Vivo</a>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h4>Mức giá {{ $category->name }}</h4>
                                    <div class="category-mega-list">
                                        <a href="{{ route('search', ['min_price' => 0, 'max_price' => 2000000, 'category' => $category->slug]) }}">Dưới 2 triệu</a>
                                        <a href="{{ route('search', ['min_price' => 2000000, 'max_price' => 4000000, 'category' => $category->slug]) }}">Từ 2 - 4 triệu</a>
                                        <a href="{{ route('search', ['min_price' => 4000000, 'max_price' => 7000000, 'category' => $category->slug]) }}">Từ 4 - 7 triệu</a>
                                        <a href="{{ route('search', ['min_price' => 7000000, 'max_price' => 13000000, 'category' => $category->slug]) }}">Từ 7 - 13 triệu</a>
                                        <a href="{{ route('search', ['min_price' => 13000000, 'max_price' => 20000000, 'category' => $category->slug]) }}">Từ 13 - 20 triệu</a>
                                        <a href="{{ route('search', ['min_price' => 20000000, 'category' => $category->slug]) }}">Trên 20 triệu</a>
                                    </div>
                                </div>
                                <div>
                                    <h4>{{ $category->name }} HOT</h4>
                                    <div class="category-mega-list">
                                        @php
                                            $hotInCategory = $featuredProducts->where('category_id', $category->id)->take(8);
                                        @endphp
                                        @forelse($hotInCategory as $product)
                                            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                        @empty
                                            @foreach($featuredProducts->take(8) as $product)
                                                <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                            @endforeach
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li>
                        <a href="#"><i class="fas fa-mobile-alt"></i> Điện thoại</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-tablet-alt"></i> Tablet</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-laptop"></i> Laptop</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-headphones"></i> Tai nghe</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-battery-full"></i> Pin & Sạc</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-memory"></i> Thẻ nhớ</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-shield-alt"></i> Ốp lưng</a>
                    </li>
                    <li>
                        <a href="#"><i class="fas fa-mobile-screen-button"></i> Điện thoại cũ</a>
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="banner-carousel" id="bannerCarousel">
                @forelse($banners as $index => $banner)
                    <div class="banner-slide {{ $index === 0 ? 'active' : '' }}">
                        <a href="{{ $banner->link ?: '#' }}">
                            @if($banner->image)
                                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}">
                            @else
                                <img src="{{ asset('images/banner/banner1.webp') }}" alt="{{ $banner->title }}">
                            @endif
                        </a>
                    </div>
                @empty
                    <div class="banner-slide active">
                        <img src="{{ asset('images/banner/banner1.webp') }}" alt="Banner">
                    </div>
                @endforelse
                
                @if($banners->count() > 1)
                    <button class="banner-prev" type="button" data-banner-prev>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="banner-next" type="button" data-banner-next>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <div class="banner-indicators">
                        @foreach($banners as $index => $banner)
                            <button type="button" class="{{ $index === 0 ? 'active' : '' }}" data-banner-indicator="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Sale Section -->
    @php
        $hasActiveFlashSale = $flashSaleProducts->isNotEmpty();
        $flashSaleTabs = [
            ['label' => 'Đang diễn ra', 'active' => $hasActiveFlashSale],
        ];
        if ($nextSaleProduct) {
            $flashSaleTabs[] = ['label' => 'Sắp diễn ra', 'active' => !$hasActiveFlashSale];
        }
        $countdownLabel = $hasActiveFlashSale ? 'Kết thúc sau:' : 'Bắt đầu sau:';
        $countdownValues = $flashSaleCountdown ?? [
            'days' => '--',
            'hours' => '--',
            'minutes' => '--',
            'seconds' => '--',
        ];
        $saleWindowText = $hasActiveFlashSale
            ? ($flashSaleProducts->pluck('sale_ends_at')->filter()->min()?->format('d/m H:i') ?? 'Hôm nay')
            : ($nextSaleProduct?->sale_starts_at?->format('d/m H:i') ?? 'Chưa có lịch');
        
        // Tính toán countdownTarget
        $countdownTarget = null;
        if ($hasActiveFlashSale) {
            $countdownTarget = $flashSaleProducts->pluck('sale_ends_at')->filter()->min();
        } elseif ($nextSaleProduct && $nextSaleProduct->sale_starts_at) {
            $countdownTarget = $nextSaleProduct->sale_starts_at;
        }
    @endphp
    <div class="flash-sale-wrapper">
        <div class="flash-sale-banner">
            <div class="flash-sale-label">Flash sale</div>
            <div class="flash-sale-heading">
                <p>{{ $hasActiveFlashSale ? 'Đang diễn ra đến ' . $saleWindowText : 'Sắp mở bán lúc ' . $saleWindowText }}</p>
                <h2>Black Fire Day</h2>
            </div>
            <div class="flash-sale-cta">
                <span>Chơi Game Trúng Lớn</span>
                <a href="#">Săn ngay</a>
            </div>
        </div>
        <div class="flash-sale-content">
            <div class="flash-sale-tabs">
                @foreach($flashSaleTabs as $tab)
                    <div class="flash-sale-tab {{ $tab['active'] ?? false ? 'active' : '' }}">{{ $tab['label'] }}</div>
                @endforeach
            </div>
            <div class="flash-sale-countdown" 
                 @if($countdownTarget) 
                 data-countdown-target="{{ $countdownTarget->timestamp }}" 
                 data-countdown-label="{{ $countdownLabel }}"
                 @endif>
                <span>{{ $countdownLabel }}</span>
                <div class="time-box" data-time="days">{{ $countdownValues['days'] }}</div>
                <div class="time-box" data-time="hours">{{ $countdownValues['hours'] }}</div>
                <div class="time-box" data-time="minutes">{{ $countdownValues['minutes'] }}</div>
                <div class="time-box" data-time="seconds">{{ $countdownValues['seconds'] }}</div>
            </div>
            <div class="flash-sale-grid">
                @forelse($flashSaleProducts as $product)
                    @php
                        $productImages = $product->images;
                        if (is_string($productImages)) {
                            $productImages = json_decode($productImages, true);
                        }
                        $imagePath = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                        $imageUrl = $imagePath ? asset('storage/' . $imagePath) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+';
                        $featureList = array_values(array_filter([
                            $product->short_description,
                            optional($product->category)->name ? 'Danh mục: ' . $product->category->name : null,
                            $product->stock !== null ? 'Kho: ' . $product->stock . ' sp' : null,
                        ]));
                    @endphp
                    <div class="flash-sale-card">
                        <div class="flash-sale-card-brand">{{ optional($product->category)->name ?? 'Flash Sale' }}</div>
                        <div class="flash-sale-card-image">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                        </div>
                        <h4>{{ $product->name }}</h4>
                        <ul class="flash-sale-features">
                            @forelse($featureList as $feature)
                                <li>{{ $feature }}</li>
                            @empty
                                <li>Sản phẩm chính hãng, mới 100%</li>
                            @endforelse
                        </ul>
                        <div class="flash-sale-pricing">
                            <div class="flash-sale-price">{{ number_format($product->final_price, 0, ',', '.') }}đ</div>
                            @if($product->is_sale_active)
                                <div class="flash-sale-price-old">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                            @endif
                        </div>
                        <div class="flash-sale-progress">
                            {{ $product->stock > 0 ? 'Còn ' . $product->stock . ' sản phẩm' : 'Đã bán hết' }}
                        </div>
                        <div style="display:flex;gap:10px;align-items:center;margin-top:12px;">
                            <form action="{{ route('cart.add') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="purchase_type" value="buy_now">
                                <button type="submit" style="background:#ff0b5d;color:#fff;padding:10px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:700;">
                                    Mua ngay
                                </button>
                            </form>
                            <a href="{{ route('product.show', $product->slug) }}" style="background:transparent;border:1px solid rgba(0,0,0,0.06);padding:10px 14px;border-radius:10px;text-decoration:none;color:#333;font-weight:700;">Xem</a>
                        </div>
                    </div>
                @empty
                    <div class="flash-sale-empty">
                        <p>Chưa có chương trình Flash Sale nào đang diễn ra.</p>
                        @if($nextSaleProduct && $nextSaleProduct->sale_starts_at)
                            <small>Chương trình tiếp theo bắt đầu lúc {{ $nextSaleProduct->sale_starts_at->format('d/m H:i') }}.</small>
                        @endif
                    </div>
                @endforelse
            </div>
            <div class="flash-sale-note">Chỉ áp dụng thanh toán online thành công — Mỗi số điện thoại chỉ được mua 1 sản phẩm cùng loại</div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="section">
        <div class="section-header">
            <h2>Danh mục sản phẩm</h2>
            <a href="{{ route('category.index') }}">Xem tất cả <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="categories-grid">
            @forelse($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                <i class="{{ $category->icon ?? 'fas fa-box' }}"></i>
                <h3>{{ $category->name }}</h3>
            </a>
            @empty
            <div class="category-card">
                <i class="fas fa-mobile-alt"></i>
                <h3>Điện thoại</h3>
            </div>
            <div class="category-card">
                <i class="fab fa-apple"></i>
                <h3>iPhone</h3>
            </div>
            <div class="category-card">
                <i class="fab fa-android"></i>
                <h3>Samsung</h3>
            </div>
            <div class="category-card">
                <i class="fab fa-android"></i>
                <h3>Xiaomi</h3>
            </div>
            <div class="category-card">
                <i class="fas fa-tablet-alt"></i>
                <h3>Tablet</h3>
            </div>
            <div class="category-card">
                <i class="fas fa-laptop"></i>
                <h3>Laptop</h3>
            </div>
            <div class="category-card">
                <i class="fas fa-headphones"></i>
                <h3>Tai nghe</h3>
            </div>
            <div class="category-card">
                <i class="fas fa-mobile-screen-button"></i>
                <h3>Máy cũ</h3>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Featured Products -->
    <div class="section">
        <div class="section-header">
            <h2>Sản phẩm nổi bật</h2>
            <a href="{{ route('category.index') }}">Xem tất cả <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="products-grid">
            @forelse($featuredProducts as $product)
            <div class="product-card">
                <div class="product-card-inner">
                    <a href="{{ route('product.show', $product->slug) }}">
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
                    @if($product->featured)
                    <div class="product-badge featured">
                        <i class="fas fa-star"></i> Nổi bật
                    </div>
                    @elseif($product->is_sale_active)
                    <div class="product-badge sale">-{{ $product->discount_percentage }}%</div>
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
                    @auth
                        @php
                            $isFav = auth()->user()->wishlists->contains('product_id', $product->id);
                        @endphp
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" style="position:absolute;top:8px;right:8px;">
                            @csrf
                            <button type="submit" class="product-wishlist-btn {{ $isFav ? 'active' : '' }}">
                                <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
            @empty
            <p>Sản phẩm sẽ hiển thị khi có dữ liệu</p>
            @endforelse
        </div>
    </div>

    <!-- Latest Products -->
    <div class="section">
        <div class="section-header">
            <h2>Sản phẩm mới nhất</h2>
            <a href="{{ route('category.index') }}">Xem tất cả <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="products-grid">
            @forelse($latestProducts as $product)
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
                    @if($product->featured)
                    <div class="product-badge featured">
                        <i class="fas fa-star"></i> Nổi bật
                    </div>
                    @elseif($product->is_sale_active)
                    <div class="product-badge sale">-{{ $product->discount_percentage }}%</div>
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
            <p>Sản phẩm sẽ hiển thị khi có dữ liệu</p>
            @endforelse
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="section" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 50px 0;">
        <div class="section-header">
            <h2><i class="fas fa-star" style="color: #ffc107;"></i> Đánh giá khách hàng</h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px;">
            @forelse($reviews as $review)
            <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;" 
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.12)';" 
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)';">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px;">
                        {{ strtoupper(substr($review->name ?? ($review->user->name ?? 'A'), 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">
                            {{ $review->name ?? $review->user->name ?? 'Khách hàng' }}
                        </div>
                        <div style="display: flex; gap: 3px; margin-bottom: 5px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#ffc107' : '#ddd' }}; font-size: 14px;"></i>
                            @endfor
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            {{ $review->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @if($review->product)
                <div style="margin-bottom: 12px; padding: 8px 12px; background: #f8f9fa; border-radius: 8px; font-size: 13px; color: #666;">
                    <i class="fas fa-shopping-bag" style="margin-right: 5px;"></i>
                    <a href="{{ route('product.show', $review->product->slug) }}" style="color: #2563eb; text-decoration: none;">
                        {{ Str::limit($review->product->name, 40) }}
                    </a>
                </div>
                @endif
                @if($review->comment)
                <div style="color: #555; line-height: 1.6; font-size: 14px;">
                    "{{ Str::limit($review->comment, 150) }}"
                </div>
                @endif
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #999;">
                <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Blog / News Section -->
    <div class="section">
        <div class="section-header">
            <h2>Tin tức, blog</h2>
            <a href="{{ route('posts.index') }}">Xem tất cả bài viết <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="products-grid">
            @php
                use Illuminate\Support\Facades\Schema;
                $latestPosts = collect();
                if (class_exists(\App\Models\Post::class) && Schema::hasTable('posts')) {
                    $latestPosts = \App\Models\Post::where('is_active', true)->latest('published_at')->latest()->take(6)->get();
                }
            @endphp
            @forelse($latestPosts as $post)
            <a href="{{ route('posts.show', $post->slug) }}" class="product-card" style="text-decoration:none;">
                <div class="product-image" style="height:160px;">
                    @if($post->image)
                        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}">
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjE2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+" alt="{{ $post->title }}">
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-title" style="height:auto;">{{ $post->title }}</div>
                    <div style="color:#666;font-size:13px;">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 90) }}</div>
                </div>
            </a>
            @empty
            <p>Bài viết sẽ hiển thị khi có dữ liệu</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let slideIndex = 0;
const slides = document.querySelectorAll('.banner-slide');
const indicators = document.querySelectorAll('.banner-indicators button');
const prevButton = document.querySelector('[data-banner-prev]');
const nextButton = document.querySelector('[data-banner-next]');
const bannerCarousel = document.getElementById('bannerCarousel');

function showSlide(index) {
    // Xử lý vị trí slide
    if (index >= slides.length) {
        slideIndex = 0;
    } else if (index < 0) {
        slideIndex = slides.length - 1;
    } else {
        slideIndex = index;
    }
    
    // Ẩn tất cả slides
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Hiển thị slide hiện tại
    if (slides[slideIndex]) {
        slides[slideIndex].classList.add('active');
        indicators[slideIndex].classList.add('active');
    }
}

function changeSlide(n) {
    showSlide(slideIndex + n);
}

function currentSlide(index) {
    showSlide(index);
}

if (prevButton) {
    prevButton.addEventListener('click', () => changeSlide(-1));
}

if (nextButton) {
    nextButton.addEventListener('click', () => changeSlide(1));
}

indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => currentSlide(index));
});

// Auto-play banner (tự động chuyển sau 5 giây)
let autoSlideInterval;

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        changeSlide(1);
    }, 5000);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

// Bắt đầu auto-play khi trang load
document.addEventListener('DOMContentLoaded', function() {
    if (slides.length > 1) {
        startAutoSlide();
        
        // Dừng auto-play khi hover
        if (bannerCarousel) {
            bannerCarousel.addEventListener('mouseenter', stopAutoSlide);
            bannerCarousel.addEventListener('mouseleave', startAutoSlide);
        }
    }
    
    // Flash Sale Countdown Timer - Tự động cập nhật mỗi giây
    const flashSaleCountdown = document.querySelector('.flash-sale-countdown');
    if (flashSaleCountdown && flashSaleCountdown.dataset.countdownTarget) {
        const targetTimestamp = parseInt(flashSaleCountdown.dataset.countdownTarget);
        const countdownLabel = flashSaleCountdown.dataset.countdownLabel || 'Kết thúc sau:';
        
        const updateFlashSaleCountdown = function() {
            const now = Math.floor(Date.now() / 1000);
            let remaining = targetTimestamp - now;
            
            if (remaining <= 0) {
                remaining = 0;
                flashSaleCountdown.querySelector('span').textContent = 'Đã kết thúc';
            }
            
            const days = Math.floor(remaining / 86400);
            remaining -= days * 86400;
            const hours = Math.floor(remaining / 3600);
            remaining -= hours * 3600;
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            
            const daysBox = flashSaleCountdown.querySelector('[data-time="days"]');
            const hoursBox = flashSaleCountdown.querySelector('[data-time="hours"]');
            const minutesBox = flashSaleCountdown.querySelector('[data-time="minutes"]');
            const secondsBox = flashSaleCountdown.querySelector('[data-time="seconds"]');
            
            if (daysBox) daysBox.textContent = String(days).padStart(2, '0');
            if (hoursBox) hoursBox.textContent = String(hours).padStart(2, '0');
            if (minutesBox) minutesBox.textContent = String(minutes).padStart(2, '0');
            if (secondsBox) secondsBox.textContent = String(seconds).padStart(2, '0');
        };
        
        // Cập nhật ngay lập tức
        updateFlashSaleCountdown();
        
        // Cập nhật mỗi giây
        setInterval(updateFlashSaleCountdown, 1000);
    }
});

</script>
@endpush

