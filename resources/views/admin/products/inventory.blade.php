@extends('layouts.admin')

@section('title', 'Quản lý kho')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-warehouse"></i> Quản lý kho</h1>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Thống kê tổng quan -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="stat-card-icon" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; background: #e3f2fd; color: #1976d2;">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-card-title" style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Tổng sản phẩm</div>
        <div class="stat-card-value" style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($totalProducts) }}</div>
    </div>
    <div class="stat-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="stat-card-icon" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; background: #e8f5e9; color: #388e3c;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-card-title" style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Còn hàng</div>
        <div class="stat-card-value" style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($inStock) }}</div>
    </div>
    <div class="stat-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="stat-card-icon" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; background: #fff3e0; color: #f57c00;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-card-title" style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Sắp hết hàng</div>
        <div class="stat-card-value" style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($lowStock) }}</div>
    </div>
    <div class="stat-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="stat-card-icon" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; background: #ffebee; color: #f44336;">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-card-title" style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Hết hàng</div>
        <div class="stat-card-value" style="font-size: 28px; font-weight: 700; color: #2c3e50;">{{ number_format($outOfStock) }}</div>
    </div>
    <div class="stat-card" style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="stat-card-icon" style="width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 15px; background: #f3e5f5; color: #9c27b0;">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-card-title" style="color: #7f8c8d; font-size: 14px; margin-bottom: 8px;">Tổng giá trị kho</div>
        <div class="stat-card-value" style="font-size: 24px; font-weight: 700; color: #2c3e50;">{{ number_format($totalStockValue) }}đ</div>
    </div>
</div>

<!-- Form tìm kiếm và lọc -->
<div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <form method="GET" action="{{ route('products.inventory') }}" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label for="search" style="display: block; margin-bottom: 5px; font-weight: 600;">Tìm kiếm theo tên</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nhập tên sản phẩm..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
        </div>
        <div style="width: 200px;">
            <label for="category_id" style="display: block; margin-bottom: 5px; font-weight: 600;">Danh mục</label>
            <select id="category_id" name="category_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="width: 200px;">
            <label for="stock_filter" style="display: block; margin-bottom: 5px; font-weight: 600;">Trạng thái kho</label>
            <select id="stock_filter" name="stock_filter" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Tất cả</option>
                <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="low_stock" {{ request('stock_filter') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng (≤10)</option>
                <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
        @if(request('search') || request('category_id') || request('stock_filter'))
        <div>
            <a href="{{ route('products.inventory') }}" class="btn-primary" style="background: #95a5a6; padding: 10px 20px;">
                <i class="fas fa-times"></i> Xóa bộ lọc
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Bảng danh sách sản phẩm -->
<div class="table-section">
    @if(request('search') || request('category_id') || request('stock_filter'))
        <div style="margin-bottom: 15px; padding: 10px; background: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 4px;">
            <strong>Kết quả tìm kiếm:</strong> Tìm thấy {{ $products->total() }} sản phẩm
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>SKU</th>
                <th>Số lượng tồn kho</th>
                <th>Giá</th>
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
                <td>{{ $product->sku ?? '-' }}</td>
                <td>
                    <span class="stock-badge stock-{{ $product->stock <= 0 ? 'out' : ($product->stock <= 10 ? 'low' : 'in') }}" 
                          style="padding: 5px 12px; border-radius: 20px; font-weight: 600; display: inline-block;">
                        {{ number_format($product->stock) }}
                    </span>
                </td>
                <td>{{ number_format($product->price) }}đ</td>
                <td>
                    @if($product->stock <= 0)
                        <span style="color: #f44336; font-weight: 600;">Hết hàng</span>
                    @elseif($product->stock <= 10)
                        <span style="color: #f57c00; font-weight: 600;">Sắp hết</span>
                    @else
                        <span style="color: #388e3c; font-weight: 600;">Còn hàng</span>
                    @endif
                </td>
                <td>
                    <button type="button" class="btn-edit update-stock-btn" 
                            data-product-id="{{ $product->id }}" 
                            data-current-stock="{{ $product->stock }}"
                            data-product-name="{{ $product->name }}"
                            style="padding: 5px 10px; font-size: 12px;">
                        <i class="fas fa-edit"></i> Cập nhật
                    </button>
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

<!-- Modal cập nhật số lượng tồn kho -->
<div id="updateStockModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div style="background: white; margin: 10% auto; padding: 30px; border-radius: 12px; max-width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <h2 style="margin-top: 0; margin-bottom: 20px;">
            <i class="fas fa-edit"></i> Cập nhật số lượng tồn kho
        </h2>
        <form id="updateStockForm">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Sản phẩm:</label>
                <div id="productName" style="padding: 10px; background: #f5f5f5; border-radius: 4px; font-weight: 600;"></div>
            </div>
            <div style="margin-bottom: 20px;">
                <label for="stockInput" style="display: block; margin-bottom: 8px; font-weight: 600;">Số lượng tồn kho:</label>
                <input type="number" id="stockInput" name="stock" min="0" step="1" 
                       style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 4px; font-size: 16px;" required>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" id="closeModal" class="btn-primary" style="background: #95a5a6; padding: 10px 20px;">
                    Hủy
                </button>
                <button type="submit" class="btn-primary" style="padding: 10px 20px;">
                    <i class="fas fa-save"></i> Lưu
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .stock-badge.stock-out {
        background: #ffebee;
        color: #f44336;
    }
    .stock-badge.stock-low {
        background: #fff3e0;
        color: #f57c00;
    }
    .stock-badge.stock-in {
        background: #e8f5e9;
        color: #388e3c;
    }
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modal = document.getElementById('updateStockModal');
    const form = document.getElementById('updateStockForm');
    const productNameDiv = document.getElementById('productName');
    const stockInput = document.getElementById('stockInput');
    const closeModalBtn = document.getElementById('closeModal');
    let currentProductId = null;

    // Mở modal khi click nút cập nhật
    document.querySelectorAll('.update-stock-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            currentProductId = this.getAttribute('data-product-id');
            const currentStock = this.getAttribute('data-current-stock');
            const productName = this.getAttribute('data-product-name');
            
            productNameDiv.textContent = productName;
            stockInput.value = currentStock;
            modal.style.display = 'block';
        });
    });

    // Đóng modal
    closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Xử lý submit form
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!currentProductId) return;

        const stock = stockInput.value;
        if (stock < 0) {
            alert('Số lượng tồn kho không được âm!');
            return;
        }

        try {
            const response = await fetch(`/admin/products/${currentProductId}/update-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ stock: parseInt(stock) })
            });

            const data = await response.json();

            if (data.success) {
                alert('Đã cập nhật số lượng tồn kho thành công!');
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Vui lòng thử lại'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.');
        }
    });
});
</script>
@endpush

