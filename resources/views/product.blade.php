@extends('layouts.app')

@section('title', $product->name . ' - AnhKiet Store')

@push('styles')
<style>
    .product-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
        gap: 40px;
        margin-bottom: 50px;
    }
    .product-hero-gallery {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        border: 1px solid #edf1ff;
        box-shadow: 0 30px 70px rgba(15, 23, 42, 0.08);
    }
    .product-hero-main {
        position: relative;
        background: linear-gradient(140deg, #f8fbff, #eef2ff);
        border-radius: 24px;
        min-height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .product-hero-main img {
        width: 82%;
        max-height: 460px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .product-hero-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.18);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #1f2937;
    }
    .product-hero-nav[data-gallery-prev] {
        left: 16px;
    }
    .product-hero-nav[data-gallery-next] {
        right: 16px;
    }
    .product-hero-thumbs {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .product-hero-thumb {
        width: 82px;
        height: 82px;
        border-radius: 18px;
        border: 2px solid transparent;
        background: #f6f7fb;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 6px;
    }
    .product-hero-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .product-hero-thumb.active {
        border-color: #2563eb;
        background: #eef2ff;
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.18);
    }
    .product-hero-info {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .product-hero-title {
        font-size: 32px;
        font-weight: 700;
        color: #0f1c3f;
    }
    .product-hero-title span {
        font-size: 15px;
        color: #6b7280;
        margin-left: 8px;
        font-weight: 500;
    }
    .product-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 14px;
        color: #6b7280;
    }
    .product-hero-meta strong {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #f97316;
        font-size: 15px;
    }
    .product-hero-price-card {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
    }
    .product-price-box {
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #e0e7ff;
        background: linear-gradient(135deg, #fdfdff, #f1f4ff);
        min-height: 120px;
    }
    .product-price-box h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #4a4f63;
    }
    .product-price {
        margin: 10px 0 4px;
        font-size: 30px;
        font-weight: 700;
        color: #0f51d8;
        line-height: 1.1;
    }
    .product-price-alt {
        color: #dc2626;
        font-size: 26px;
        white-space: nowrap;
    }
    .product-price-box small {
        font-size: 13px;
        color: #94a3b8;
    }
    .product-option-group {
        margin-top: 12px;
    }
    .product-option-title {
        font-weight: 600;
        margin-bottom: 12px;
        color: #111827;
    }
    .product-option-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .product-option-chip {
        border: 1px solid #e1e7ff;
        border-radius: 14px;
        padding: 12px 18px;
        background: #fff;
        min-width: 110px;
        text-align: center;
        font-weight: 600;
        color: #2c3344;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .product-option-chip small {
        display: block;
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-top: 4px;
    }
    .product-option-chip.active {
        border-color: #2563eb;
        color: #1d4ed8;
        background: #eef2ff;
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.18);
    }
    .product-option-chip.color {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-width: 150px;
    }
    .color-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(0, 0, 0, 0.08);
    }
    .product-hero-benefit {
        border: 1px dashed #fdba74;
        background: #fff7ed;
        border-radius: 16px;
        padding: 16px 18px;
        font-weight: 600;
        color: #c2410c;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .product-hero-actions {
        margin-top: 8px;
        border: 1px solid #ffe0cc;
        border-radius: 18px;
        padding: 24px;
        background: #fffdfb;
        box-shadow: inset 0 0 10px rgba(255, 214, 194, 0.3);
    }
    .product-hero-quantity {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
    }
    .quantity-label {
        font-weight: 600;
        color: #1f2937;
    }
    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        overflow: hidden;
    }
    .quantity-control button {
        background: #f3f4f6;
        border: none;
        width: 48px;
        height: 48px;
        font-size: 20px;
        cursor: pointer;
    }
    .quantity-control input {
        width: 80px;
        text-align: center;
        border: none;
        font-size: 16px;
        font-weight: 600;
    }
    .product-hero-buttons {
        display: grid;
        grid-template-columns: 0.9fr 1.2fr 1fr;
        gap: 10px;
        margin-top: 16px;
        align-items: stretch;
    }
    .product-hero-buttons button {
        padding: 14px 18px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 15px;
        border: 2px solid transparent;
        text-align: center;
        cursor: pointer;
        height: 56px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-line {
        background: #f5f8ff;
        border-color: #3b82f6;
        color: #1d4ed8;
    }
    .btn-line:hover {
        background: #e0ecff;
    }
    .btn-primary {
        background: linear-gradient(120deg, #ff3b30, #ff7b1c);
        color: #fff;
        box-shadow: 0 10px 24px rgba(248, 113, 113, 0.45);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .btn-primary span {
        display: block;
        font-weight: 500;
        font-size: 12px;
        margin-top: 2px;
        text-transform: none;
        letter-spacing: normal;
    }
    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        box-shadow: none;
    }
    .btn-secondary {
        background: #fff;
        color: #b91c1c;
        border-color: #fecaca;
        font-weight: 600;
        flex-direction: row;
        gap: 8px;
        font-size: 14px;
    }
    .btn-secondary:disabled {
        background: #f5f5f5;
        color: #999;
        border-color: #ddd;
        cursor: not-allowed;
        opacity: 0.6;
    }
    .btn-secondary i {
        font-size: 18px;
    }
    .trade-in-box {
        margin-top: 14px;
        border: 1px solid #ffdede;
        border-radius: 16px;
        padding: 16px;
        background: #fff8f5;
    }
    .trade-in-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .trade-in-title {
        font-weight: 600;
        color: #c0392b;
        margin: 0;
    }
    .trade-in-content {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .trade-in-select {
        flex: 1;
        min-width: 200px;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        font-size: 14px;
        background: #fff;
    }
    .trade-in-button {
        padding: 12px 18px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(120deg, #ff8f1f, #ff6b00);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }
    .product-description {
        margin: 40px 0;
        line-height: 1.8;
        color: #4b5563;
    }
    .related-products {
        margin-top: 60px;
    }
    .section-header {
        margin-bottom: 30px;
    }
    .section-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        color: #0f172a;
        display: block;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    }
    .product-image {
        width: 100%;
        height: 210px;
        background: #f5f7ff;
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
        top: 14px;
        left: 14px;
        background: #ef4444;
        color: white;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .product-info {
        padding: 18px;
    }
    .product-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 10px;
        height: 44px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .product-price {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }
    .price-current {
        font-size: 18px;
        font-weight: 700;
        color: #dc2626;
    }
    .price-old {
        font-size: 14px;
        color: #94a3b8;
        text-decoration: line-through;
    }
    @media (max-width: 992px) {
        .product-hero {
            grid-template-columns: 1fr;
        }
        .product-hero-gallery {
            position: relative;
            top: 0;
        }
    }
    @media (max-width: 576px) {
        .product-hero-gallery {
            padding: 20px;
        }
        .product-hero-main {
            min-height: 320px;
        }
        .product-hero-buttons button,
        .product-hero-buttons a {
            min-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <div style="padding: 20px 0;">
        <a href="{{ route('home') }}">Trang chủ</a> > 
        <a href="{{ route('category.show', $product->category->slug) }}">{{ $product->category->name }}</a> > 
        <span>{{ $product->name }}</span>
    </div>

    <!-- Product Detail -->
    @php
        $rawImages = $product->images;
        if (is_string($rawImages)) {
            $rawImages = json_decode($rawImages, true);
        }
        $productImages = collect($rawImages ?? [])->filter()->map(function ($image) {
            return \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                ? $image
                : asset('storage/' . ltrim($image, '/'));
        })->values()->all();
        if (empty($productImages)) {
            $productImages[] = $defaultImageUrl;
        }
        $tradeInPrice = max((float) $product->final_price - 2000000, 0);
        $sku = $product->sku ?? 'Đang cập nhật';

        $listPrice = (float) $product->price;
        $finalPrice = (float) $product->final_price;
        $discountPercent = $listPrice > 0 && $finalPrice < $listPrice
            ? round((($listPrice - $finalPrice) / $listPrice) * 100)
            : 0;
        $monthlyInstallment = $finalPrice > 0 ? floor($finalPrice / 12 / 1000) * 1000 : 0;
    @endphp
            <div class="product-hero" data-product-hero>
        <div class="product-hero-gallery">
            <div class="product-hero-main">
                @if(count($productImages) > 1)
                <button type="button" class="product-hero-nav" data-gallery-prev>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="product-hero-nav" data-gallery-next>
                    <i class="fas fa-chevron-right"></i>
                </button>
                @endif
                <img src="{{ $productImages[0] }}" alt="{{ $product->name }}" data-product-main data-default-src="{{ $productImages[0] }}">
            </div>
            @if(count($productImages) > 1)
            <div class="product-hero-thumbs">
                @foreach($productImages as $index => $image)
                <button type="button" class="product-hero-thumb {{ $index === 0 ? 'active' : '' }}" data-product-thumb data-image="{{ $image }}">
                    <img src="{{ $image }}" alt="{{ $product->name }} thumbnail {{ $index + 1 }}">
                </button>
                @endforeach
            </div>
            @endif
        </div>
        <div class="product-hero-info">
            <div class="product-hero-title">
                {{ $product->name }} <span>| Chính hãng</span>
            </div>
            <div class="product-hero-meta">
                <strong><i class="fas fa-star"></i> 5.0</strong>
                <span>(2 đánh giá)</span>
                <span>•</span>
                <span>Mã SP: {{ $sku }}</span>
                <span>•</span>
                <span>{{ $product->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}</span>
            </div>
            <div class="product-hero-price-card">
                <div class="product-price-box">
                    <h4>Giá bán</h4>
                    <div class="product-price" data-product-price>
                        <span data-product-price-number>{{ number_format($finalPrice, 0, ',', '.') }}</span>đ
                    </div>
                    @if($discountPercent > 0)
                        <small>
                            Giá niêm yết
                            <span style="text-decoration: line-through; color:#9ca3af;">
                                <span data-product-list-price-number>{{ number_format($listPrice, 0, ',', '.') }}</span>đ
                            </span>
                            <span style="color:#ef4444; font-weight:600; margin-left:4px;">
                                -{{ $discountPercent }}%
                            </span>
                        </small>
                    @else
                        <small>Giá đã bao gồm VAT</small>
                    @endif
                </div>
                <div class="product-price-box">
                    <h4>Trả góp 0% chỉ từ</h4>
                    <div class="product-price product-price-alt">
                        <span data-product-monthly-number>{{ number_format($monthlyInstallment, 0, ',', '.') }}</span>đ/tháng
                    </div>
                    <small>Áp dụng khi thanh toán qua thẻ tín dụng, trả góp 12 tháng.</small>
                </div>
            </div>

            @if(!empty($sizeOptions ?? []))
            <div class="product-option-group">
                <div class="product-option-title">Phiên bản</div>
                <div class="product-option-list" data-product-sizes>
                    @foreach(($sizeOptions ?? []) as $index => $option)
                    <button type="button"
                        class="product-option-chip {{ $index === 0 ? 'active' : '' }}"
                        data-product-size
                        data-size-label="{{ $option['label'] }}"
                        data-size-description="{{ $option['description'] }}"
                        data-size-final-price="{{ $option['final_price'] ?? $finalPrice }}"
                        data-size-list-price="{{ $option['list_price'] ?? $listPrice }}">
                        {{ $option['label'] }}
                        @if(!empty($option['description']))
                        <small>{{ $option['description'] }}</small>
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($colorOptions ?? []))
            <div class="product-option-group">
                <div class="product-option-title">Màu sắc</div>
                <div class="product-option-list" data-product-colors>
                    @foreach(($colorOptions ?? []) as $index => $option)
                        @php
                            $imagePath = $option['image'] ?? null;
                            $colorImage = null;
                            if ($imagePath) {
                                $colorImage = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
                                    ? $imagePath
                                    : asset('storage/' . ltrim($imagePath, '/'));
                            }
                            $hex = $option['hex'] ?? '#f1f1f1';
                        @endphp
                        <button type="button"
                            class="product-option-chip color {{ $index === 0 ? 'active' : '' }}"
                            data-product-color
                            data-color-name="{{ $option['name'] }}"
                            data-color-image="{{ $colorImage }}">
                            <span class="color-dot" style="background: <?php echo e($hex); ?>;"></span>
                            <div>
                                <div>{{ $option['name'] }}</div>
                                <small>{{ number_format($product->final_price) }}đ</small>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="product-hero-benefit">
                <i class="fas fa-gift"></i>
                <div>
                    <div>Ưu đãi thêm khi mua tại AnhKiet Store:</div>
                    <ul style="margin:8px 0 0 18px; padding:0; font-weight:500; font-size:13px;">
                        <li>Giảm thêm cho thành viên Smember khi đăng nhập.</li>
                        <li>Tặng gói bảo hành 12 tháng chính hãng.</li>
                        <li>Miễn phí giao hàng nội thành cho đơn từ 1.000.000đ.</li>
                    </ul>
                </div>
            </div>

            <div class="product-hero-actions">
                @if($product->stock > 0)
                <p style="margin:0 0 16px;color:#15803d;font-weight:600;">
                    <i class="fas fa-check-circle"></i> Còn {{ $product->stock }} sản phẩm tại kho
                </p>
                @else
                <p style="margin:0 0 16px;color:#dc2626;font-weight:600;">
                    <i class="fas fa-times-circle"></i> Sản phẩm tạm hết hàng
                </p>
                @endif
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    @if(!empty($colorOptions ?? []))
                        <input type="hidden" name="selected_color" id="selectedColorInput" value="{{ data_get($colorOptions, '0.name') }}">
                    @endif
                    @if(!empty($sizeOptions ?? []))
                        <input type="hidden" name="selected_size" id="selectedSizeInput" value="{{ data_get($sizeOptions, '0.label') }}">
                    @endif
                    <div class="product-hero-quantity">
                        <div class="quantity-label">Số lượng</div>
                        <div class="quantity-control">
                            <button type="button" data-qty-minus>-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ max($product->stock, 1) }}" data-qty-input>
                            <button type="button" data-qty-plus>+</button>
                        </div>
                    </div>
                    <div class="product-hero-buttons">
                        <button type="button" class="btn-line" onclick="alert('Liên hệ hotline 1900.9999 để được tư vấn trả góp 0%.')">
                            Trả góp 0%
                        </button>
                        <button type="submit" class="btn-primary" name="purchase_type" value="buy_now" {{ $product->stock > 0 ? '' : 'disabled' }}>
                            MUA NGAY
                            <span>Giao nhanh từ 2 giờ hoặc nhận tại cửa hàng</span>
                        </button>
                        <button type="submit" class="btn-secondary" name="purchase_type" value="add_to_cart" {{ $product->stock > 0 ? '' : 'disabled' }}>
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ
                        </button>
                    </div>
                    <div class="trade-in-box">
                        <div>
                            <div class="trade-in-title">Thu cũ lên đời</div>
                            <div style="color:#555;font-size:13px;">Chỉ từ {{ number_format($tradeInPrice, 0, ',', '.') }}đ</div>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <select class="trade-in-select">
                                <option value="">Tìm sản phẩm muốn thu cũ</option>
                                <option>iPhone 13 Pro Max</option>
                                <option>iPhone 12 Pro</option>
                                <option>Samsung S22 Ultra</option>
                            </select>
                            <button type="button" class="trade-in-button">Kiểm tra ngay</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($product->short_description)
    <div class="product-description">
        {!! $product->short_description !!}
    </div>
    @endif

    @if($product->description)
    <div class="product-description" style="margin-top: 20px;">
        <h3>Mô tả sản phẩm</h3>
        {!! $product->description !!}
    </div>
    @endif

    <!-- Reviews Section -->
    <div style="margin-top: 50px; padding: 30px 0; border-top: 2px solid #eee;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h2 style="margin: 0 0 10px 0; font-size: 24px; color: #2c3e50;">
                    <i class="fas fa-star" style="color: #ffc107;"></i> Đánh giá sản phẩm
                </h2>
                @if($totalReviews > 0)
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="font-size: 32px; font-weight: 700; color: #2c3e50;">{{ number_format($averageRating, 1) }}</span>
                        <div style="display: flex; gap: 2px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color: {{ $i <= round($averageRating) ? '#ffc107' : '#ddd' }}; font-size: 20px;"></i>
                            @endfor
                        </div>
                    </div>
                    <span style="color: #666;">({{ $totalReviews }} đánh giá)</span>
                </div>
                @else
                <p style="color: #999; margin: 0;">Chưa có đánh giá nào</p>
                @endif
            </div>
        </div>

        <!-- Form đánh giá -->
        <div style="background: #f8f9fa; border-radius: 12px; padding: 25px; margin-bottom: 30px;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px;">Viết đánh giá của bạn</h3>
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                @if(session('success'))
                <div style="background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @guest
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tên của bạn *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                @endguest

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Đánh giá *</label>
                    <div style="display: flex; gap: 5px; align-items: center;">
                        @for($i = 1; $i <= 5; $i++)
                        <label style="cursor: pointer;">
                            <input type="radio" name="rating" value="{{ $i }}" required {{ old('rating') == $i ? 'checked' : '' }} style="display: none;">
                            <i class="fas fa-star rating-star" data-rating="{{ $i }}" style="font-size: 32px; color: #ddd; transition: color 0.2s;"></i>
                        </label>
                        @endfor
                        <span id="rating-text" style="margin-left: 10px; color: #666; font-weight: 600;"></span>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nhận xét</label>
                    <textarea name="comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
                              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; resize: vertical;">{{ old('comment') }}</textarea>
                </div>

                <button type="submit" style="background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-paper-plane"></i> Gửi đánh giá
                </button>
            </form>
        </div>

        <!-- Danh sách đánh giá -->
        @if($reviews->count() > 0)
        <div>
            <h3 style="margin: 0 0 20px 0; font-size: 18px;">Đánh giá từ khách hàng</h3>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach($reviews as $review)
                <div style="background: white; border: 1px solid #eee; border-radius: 12px; padding: 20px;">
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
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                    @if($review->comment)
                    <div style="color: #555; line-height: 1.6; font-size: 14px;">
                        {{ $review->comment }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: #999;">
            <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
            <p>Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
        </div>
        @endif
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="related-products">
        <div class="section-header">
            <h2>Sản phẩm liên quan</h2>
        </div>
        <div class="products-grid">
            @foreach($relatedProducts as $related)
            <a href="{{ route('product.show', $related->slug) }}" class="product-card">
                <div class="product-image">
                    @php
                        $relatedImages = $related->images;
                        if (is_string($relatedImages)) {
                            $relatedImages = json_decode($relatedImages, true);
                        }
                        $firstRelatedImage = is_array($relatedImages) && count($relatedImages) > 0 ? $relatedImages[0] : null;
                    @endphp
                    @if($firstRelatedImage)
                        <img src="{{ asset('storage/' . $firstRelatedImage) }}" alt="{{ $related->name }}">
                    @else
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+" alt="{{ $related->name }}">
                    @endif
                    @if($related->is_sale_active)
                    <div class="product-badge">-{{ $related->discount_percentage }}%</div>
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-title">{{ $related->name }}</div>
                    <div class="product-price">
                        <span class="price-current">{{ number_format($related->final_price) }}đ</span>
                        @if($related->is_sale_active)
                        <span class="price-old">{{ number_format($related->price) }}đ</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hero = document.querySelector('[data-product-hero]');
    let mainImage = null;
    let defaultSrc = null;
    let thumbButtons = [];
    let currentSlide = 0;

    if (hero) {
        mainImage = hero.querySelector('[data-product-main]');
        defaultSrc = mainImage ? mainImage.getAttribute('data-default-src') : null;
        thumbButtons = hero.querySelectorAll('[data-product-thumb]');
        const prevButton = hero.querySelector('[data-gallery-prev]');
        const nextButton = hero.querySelector('[data-gallery-next]');

        const activateThumb = (index) => {
            if (!thumbButtons.length || !mainImage) {
                return;
            }
            thumbButtons.forEach(btn => btn.classList.remove('active'));
            const target = thumbButtons[index];
            if (target) {
                target.classList.add('active');
                mainImage.src = target.getAttribute('data-image') || defaultSrc || mainImage.src;
            }
        };

        const changeSlide = (delta) => {
            if (!thumbButtons.length) {
                return;
            }
            currentSlide = (currentSlide + delta + thumbButtons.length) % thumbButtons.length;
            activateThumb(currentSlide);
        };

        thumbButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                currentSlide = index;
                activateThumb(index);
            });
        });

        if (prevButton) {
            prevButton.addEventListener('click', () => changeSlide(-1));
        }
        if (nextButton) {
            nextButton.addEventListener('click', () => changeSlide(1));
        }
        if (thumbButtons.length) {
            activateThumb(0);
        }

        const colorButtons = hero.querySelectorAll('[data-product-color]');
        const colorInput = document.getElementById('selectedColorInput');
        colorButtons.forEach(button => {
            button.addEventListener('click', () => {
                colorButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                if (colorInput) {
                    colorInput.value = button.getAttribute('data-color-name') || '';
                }

                const colorImage = button.getAttribute('data-color-image');
                if (colorImage && mainImage) {
                    mainImage.src = colorImage;
                } else if (!colorImage && thumbButtons.length) {
                    activateThumb(currentSlide);
                } else if (!colorImage && defaultSrc && mainImage) {
                    mainImage.src = defaultSrc;
                }
            });
        });

        const sizeButtons = hero.querySelectorAll('[data-product-size]');
        const sizeInput = document.getElementById('selectedSizeInput');
        const priceNumberEl = hero.querySelector('[data-product-price-number]');
        const listPriceNumberEl = hero.querySelector('[data-product-list-price-number]');
        const monthlyNumberEl = hero.querySelector('[data-product-monthly-number]');

        const formatCurrency = (value) => {
            if (typeof value !== 'number' || isNaN(value)) return '';
            return value.toLocaleString('vi-VN', { maximumFractionDigits: 0 });
        };

        sizeButtons.forEach(button => {
            button.addEventListener('click', () => {
                sizeButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                if (sizeInput) {
                    sizeInput.value = button.getAttribute('data-size-label') || '';
                }

                const finalPrice = parseFloat(button.getAttribute('data-size-final-price') || '0');
                const listPrice = parseFloat(button.getAttribute('data-size-list-price') || '0');

                if (priceNumberEl && finalPrice > 0) {
                    priceNumberEl.textContent = formatCurrency(finalPrice);
                }
                if (listPriceNumberEl && listPrice > 0) {
                    listPriceNumberEl.textContent = formatCurrency(listPrice);
                }
                if (monthlyNumberEl && finalPrice > 0) {
                    const monthly = Math.floor(finalPrice / 12 / 1000) * 1000;
                    monthlyNumberEl.textContent = formatCurrency(monthly);
                }
            });
        });
    }

    const qtyInput = document.querySelector('[data-qty-input]');
    const minusBtn = document.querySelector('[data-qty-minus]');
    const plusBtn = document.querySelector('[data-qty-plus]');
    if (qtyInput && minusBtn && plusBtn) {
        const max = parseInt(qtyInput.getAttribute('max') || '999', 10);

        minusBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value || '1', 10);
            qtyInput.value = Math.max(1, current - 1);
        });

        plusBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value || '1', 10);
            qtyInput.value = Math.min(max, current + 1);
        });

        qtyInput.addEventListener('change', () => {
            let current = parseInt(qtyInput.value || '1', 10);
            if (isNaN(current) || current < 1) current = 1;
            if (current > max) current = max;
            qtyInput.value = current;
        });
    }

    // Rating stars interaction
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingText = document.getElementById('rating-text');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingTexts = {
        1: 'Rất không hài lòng',
        2: 'Không hài lòng',
        3: 'Bình thường',
        4: 'Hài lòng',
        5: 'Rất hài lòng'
    };

    ratingStars.forEach((star, index) => {
        const rating = index + 1;
        star.addEventListener('mouseenter', () => {
            for (let i = 0; i <= index; i++) {
                ratingStars[i].style.color = '#ffc107';
            }
            for (let i = index + 1; i < 5; i++) {
                ratingStars[i].style.color = '#ddd';
            }
            if (ratingText) {
                ratingText.textContent = ratingTexts[rating];
            }
        });

        star.addEventListener('click', () => {
            ratingInputs[rating - 1].checked = true;
            updateRatingDisplay(rating);
        });
    });

    // Reset stars on mouse leave
    const ratingContainer = document.querySelector('.rating-star')?.closest('div');
    if (ratingContainer) {
        ratingContainer.addEventListener('mouseleave', () => {
            const checked = document.querySelector('input[name="rating"]:checked');
            if (checked) {
                updateRatingDisplay(parseInt(checked.value));
            } else {
                ratingStars.forEach(star => star.style.color = '#ddd');
                if (ratingText) ratingText.textContent = '';
            }
        });
    }

    function updateRatingDisplay(rating) {
        ratingStars.forEach((star, index) => {
            star.style.color = index < rating ? '#ffc107' : '#ddd';
        });
        if (ratingText) {
            ratingText.textContent = ratingTexts[rating];
        }
    }

    // Initialize display if rating is already selected
    const initialChecked = document.querySelector('input[name="rating"]:checked');
    if (initialChecked) {
        updateRatingDisplay(parseInt(initialChecked.value));
    }
});
</script>
@endpush
