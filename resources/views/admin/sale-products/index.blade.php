@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm Sale')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-left: 4px solid #3498db;
    }
    .stat-card.active { border-left-color: #2ecc71; }
    .stat-card.upcoming { border-left-color: #f39c12; }
    .stat-card.ended { border-left-color: #95a5a6; }
    .stat-card.no-sale { border-left-color: #e74c3c; }
    .stat-card h3 {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: 700;
        color: #2c3e50;
    }
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .filter-tab {
        padding: 10px 20px;
        background: white;
        border: 2px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #666;
        font-weight: 600;
        transition: all 0.3s;
    }
    .filter-tab:hover {
        border-color: #3498db;
        color: #3498db;
    }
    .filter-tab.active {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }
    .bulk-actions {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .bulk-actions h3 {
        margin: 0 0 15px 0;
        color: #856404;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bulk-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    .sale-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 700;
        background: #ff6b6b;
        color: white;
    }
    .sale-badge.upcoming {
        background: #f39c12;
    }
    .sale-badge.ended {
        background: #95a5a6;
    }
    .product-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    .action-buttons {
        display: flex;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="fas fa-fire"></i> Quản lý Sản phẩm Sale</h1>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Tổng sản phẩm</h3>
        <div class="value">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card active">
        <h3>Đang Sale</h3>
        <div class="value">{{ $stats['active'] }}</div>
    </div>
    <div class="stat-card upcoming">
        <h3>Sắp Sale</h3>
        <div class="value">{{ $stats['upcoming'] }}</div>
    </div>
    <div class="stat-card ended">
        <h3>Đã Kết thúc</h3>
        <div class="value">{{ $stats['ended'] }}</div>
    </div>
    <div class="stat-card no-sale">
        <h3>Không Sale</h3>
        <div class="value">{{ $stats['no_sale'] }}</div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('sale-products.index', ['status' => 'all'] + request()->except(['status', 'page'])) }}" 
       class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
        Tất cả
    </a>
    <a href="{{ route('sale-products.index', ['status' => 'active'] + request()->except(['status', 'page'])) }}" 
       class="filter-tab {{ $status === 'active' ? 'active' : '' }}">
        Đang Sale
    </a>
    <a href="{{ route('sale-products.index', ['status' => 'upcoming'] + request()->except(['status', 'page'])) }}" 
       class="filter-tab {{ $status === 'upcoming' ? 'active' : '' }}">
        Sắp Sale
    </a>
    <a href="{{ route('sale-products.index', ['status' => 'ended'] + request()->except(['status', 'page'])) }}" 
       class="filter-tab {{ $status === 'ended' ? 'active' : '' }}">
        Đã Kết thúc
    </a>
    <a href="{{ route('sale-products.index', ['status' => 'no_sale'] + request()->except(['status', 'page'])) }}" 
       class="filter-tab {{ $status === 'no_sale' ? 'active' : '' }}">
        Không Sale
    </a>
</div>

<!-- Bulk Actions -->
<div class="bulk-actions">
    <h3><i class="fas fa-tasks"></i> Thiết lập Sale hàng loạt</h3>
    <form id="bulk-sale-form" method="POST" action="{{ route('sale-products.bulk-set-sale') }}">
        @csrf
        <div class="bulk-form-grid">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">% Giảm giá</label>
                <input type="number" name="discount_percent" min="0" max="100" step="1" placeholder="VD: 20" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <small style="color: #666;">Tự động tính giá sale từ giá gốc</small>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Giá sale (VNĐ)</label>
                <input type="number" name="sale_price" min="0" step="1000" placeholder="VD: 20000000" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Bắt đầu</label>
                <input type="datetime-local" name="sale_starts_at" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Kết thúc</label>
                <input type="datetime-local" name="sale_ends_at" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                <i class="fas fa-fire"></i> Áp dụng Sale cho sản phẩm đã chọn
            </button>
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="is_flash_sale" value="1" checked>
                <span>Tham gia Flash Sale</span>
            </label>
        </div>
    </form>
</div>

<!-- Search & Filter -->
<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('sale-products.index') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <input type="hidden" name="status" value="{{ $status }}">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên sản phẩm..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="width: 200px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Danh mục</label>
            <select name="category_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Tất cả</option>
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
            <a href="{{ route('sale-products.index', ['status' => $status]) }}" class="btn-primary" style="background: #95a5a6; padding: 10px 20px;">
                <i class="fas fa-times"></i> Xóa lọc
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Products Table -->
<div class="table-section">
    <form id="products-form">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="select-all" class="product-checkbox">
                    </th>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá gốc</th>
                    <th>Giá sale</th>
                    <th>% Giảm</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $productImages = $product->images;
                    if (is_string($productImages)) {
                        $productImages = json_decode($productImages, true);
                    }
                    $firstImage = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                    $now = now();
                    $isActive = $product->is_flash_sale && $product->sale_price && $product->sale_price < $product->price
                        && (!$product->sale_starts_at || $product->sale_starts_at <= $now)
                        && (!$product->sale_ends_at || $product->sale_ends_at >= $now);
                    $isUpcoming = $product->is_flash_sale && $product->sale_starts_at && $product->sale_starts_at > $now;
                    $isEnded = $product->is_flash_sale && $product->sale_ends_at && $product->sale_ends_at < $now;
                    $discountPercent = $product->sale_price && $product->price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
                @endphp
                <tr>
                    <td>
                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox product-check">
                    </td>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span class="badge badge-danger">No image</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $product->name }}</strong><br>
                        <small style="color: #666;">{{ $product->category->name ?? 'N/A' }}</small>
                    </td>
                    <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                    <td>
                        @if($product->sale_price)
                            <strong style="color: #d32f2f;">{{ number_format($product->sale_price, 0, ',', '.') }}đ</strong>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($discountPercent > 0)
                            <span class="sale-badge {{ $isActive ? '' : ($isUpcoming ? 'upcoming' : ($isEnded ? 'ended' : '')) }}">
                                -{{ $discountPercent }}%
                            </span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td style="font-size: 12px;">
                        @if($product->sale_starts_at)
                            <div><strong>Bắt đầu:</strong> {{ $product->sale_starts_at->format('d/m/Y H:i') }}</div>
                        @endif
                        @if($product->sale_ends_at)
                            <div><strong>Kết thúc:</strong> {{ $product->sale_ends_at->format('d/m/Y H:i') }}</div>
                        @endif
                        @if(!$product->sale_starts_at && !$product->sale_ends_at)
                            <span style="color: #999;">Không giới hạn</span>
                        @endif
                    </td>
                    <td>
                        @if($isActive)
                            <span class="sale-badge">Đang Sale</span>
                        @elseif($isUpcoming)
                            <span class="sale-badge upcoming">Sắp Sale</span>
                        @elseif($isEnded)
                            <span class="sale-badge ended">Đã Kết thúc</span>
                        @else
                            <span style="color: #999;">Không Sale</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-edit" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($product->is_flash_sale)
                            <form action="{{ route('sale-products.remove-sale', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn gỡ sale cho sản phẩm này?')">
                                @csrf
                                <button type="submit" class="btn-delete" title="Gỡ Sale">
                                    <i class="fas fa-fire"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 40px;">
                        <i class="fas fa-box-open" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                        <p style="color: #999;">Chưa có sản phẩm nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </form>

    <div class="pagination">
        {{ $products->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAll = document.getElementById('select-all');
    const productChecks = document.querySelectorAll('.product-check');
    
    selectAll.addEventListener('change', function() {
        productChecks.forEach(check => {
            check.checked = this.checked;
        });
    });
    
    productChecks.forEach(check => {
        check.addEventListener('change', function() {
            const allChecked = Array.from(productChecks).every(c => c.checked);
            selectAll.checked = allChecked;
        });
    });
    
    // Bulk form submit
    document.getElementById('bulk-sale-form').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.product-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một sản phẩm!');
            return false;
        }
        
        // Add checked product IDs to form
        checked.forEach(check => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = check.value;
            this.appendChild(input);
        });
    });
});
</script>
@endpush

