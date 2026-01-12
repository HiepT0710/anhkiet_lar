@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Sửa sản phẩm</h1>
    <a href="{{ route('products.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="table-section">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; gap: 20px;">
            <div>
                <label for="name">Tên sản phẩm *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="slug">Slug *</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description', $product->description) }}</textarea>
            </div>
            
            <div>
                <label for="short_description">Mô tả ngắn</label>
                <textarea id="short_description" name="short_description" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('short_description', $product->short_description) }}</textarea>
            </div>
            
            <div>
                <label for="images">Hình ảnh sản phẩm</label>
                <input type="file" id="images" name="images[]" multiple accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <small style="color: #666;">Có thể chọn nhiều ảnh cùng lúc</small>
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <div style="margin-top: 10px;">
                        <strong>Ảnh hiện tại:</strong>
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Product Image" style="width: 60px; height: 60px; object-fit: cover; margin: 5px; border-radius: 4px; border: 1px solid #ddd;">
                        @endforeach
                    </div>
                @endif
            </div>

            @php
                $oldColorNames = old('color_names');
                $oldColorHexes = old('color_hexes');
                $oldColorImages = old('color_images');
                $colorRows = [];

                if (is_array($oldColorNames) || is_array($oldColorHexes) || is_array($oldColorImages)) {
                    $max = max(count($oldColorNames ?? []), count($oldColorHexes ?? []), count($oldColorImages ?? []));
                    for ($i = 0; $i < $max; $i++) {
                        $colorRows[] = [
                            'name' => $oldColorNames[$i] ?? '',
                            'hex' => $oldColorHexes[$i] ?? '#000000',
                            'image' => $oldColorImages[$i] ?? '',
                        ];
                    }
                } elseif (is_array($product->color_options) && count($product->color_options)) {
                    foreach ($product->color_options as $option) {
                        $colorRows[] = [
                            'name' => $option['name'] ?? '',
                            'hex' => $option['hex'] ?? '#000000',
                            'image' => $option['image'] ?? '',
                        ];
                    }
                }

                if (!count($colorRows)) {
                    $colorRows[] = ['name' => '', 'hex' => '#000000', 'image' => ''];
                }
            @endphp
            <div>
                <label>Phiên bản màu sắc</label>
                <div class="color-options-admin" data-color-options>
                    @foreach($colorRows as $row)
                    <div class="color-option-row" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" name="color_names[]" value="{{ $row['name'] }}" placeholder="Tên màu (VD: Titan Đen)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        <input type="color" name="color_hexes[]" value="{{ $row['hex'] ?: '#000000' }}" title="Mã màu" style="width:70px; height:42px; border:1px solid #ddd; border-radius:4px; padding:0;">
                        <input type="text" name="color_images[]" value="{{ $row['image'] }}" placeholder="Đường dẫn ảnh (tùy chọn)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        <button type="button" class="remove-color-option" title="Xóa" style="background:#f8d7da;border:1px solid #f5c2c7;color:#b02a37;padding:10px 14px;border-radius:4px;cursor:pointer;">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-primary" data-add-color style="margin-top:10px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-plus"></i> Thêm màu
                </button>
                <small style="color:#666; display:block; margin-top:8px;">Có thể thêm nhiều màu. Mỗi màu gồm tên, mã màu và đường dẫn ảnh riêng (nếu có).</small>
            </div>

            @php
                $oldSizeLabels = old('size_labels');
                $oldSizeDescriptions = old('size_descriptions');
                $sizeRows = [];

                if (is_array($oldSizeLabels) || is_array($oldSizeDescriptions)) {
                    $max = max(count($oldSizeLabels ?? []), count($oldSizeDescriptions ?? []));
                    for ($i = 0; $i < $max; $i++) {
                        $sizeRows[] = [
                            'label' => $oldSizeLabels[$i] ?? '',
                            'description' => $oldSizeDescriptions[$i] ?? '',
                        ];
                    }
                } elseif (is_array($product->size_options) && count($product->size_options)) {
                    foreach ($product->size_options as $option) {
                        $sizeRows[] = [
                            'label' => $option['label'] ?? '',
                            'description' => $option['description'] ?? '',
                        ];
                    }
                }

                if (!count($sizeRows)) {
                    $sizeRows[] = ['label' => '', 'description' => ''];
                }
            @endphp
            <div>
                <label>Kích thước / dung lượng</label>
                <div class="size-options-admin" data-size-options>
                    @foreach($sizeRows as $row)
                    <div class="size-option-row" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" name="size_labels[]" value="{{ $row['label'] }}" placeholder="Ví dụ: S, M, L hoặc 64GB, 128GB" style="flex:1; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        <input type="text" name="size_descriptions[]" value="{{ $row['description'] }}" placeholder="Ghi chú (tùy chọn)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                        <button type="button" class="remove-size-option" title="Xóa" style="background:#f8d7da;border:1px solid #f5c2c7;color:#b02a37;padding:10px 14px;border-radius:4px;cursor:pointer;">×</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-primary" data-add-size style="margin-top:10px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fas fa-plus"></i> Thêm kích thước
                </button>
                <small style="color:#666; display:block; margin-top:8px;">Có thể thêm kích thước (S/M/L/XL) hoặc dung lượng bộ nhớ (64GB/128GB/256GB).</small>
            </div>
            
            <!-- Flash Sale Section -->
            <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 20px; margin: 20px 0;">
                <h3 style="margin: 0 0 15px 0; color: #856404; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-fire" style="color: #ff6b6b;"></i> Cài đặt Flash Sale
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <div>
                        <label for="price">Giá gốc (VNĐ) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required min="0" step="1000" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" oninput="calculateDiscount()">
                    </div>
                    
                    <div>
                        <label for="sale_price">Giá sale (VNĐ)</label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" oninput="calculateDiscount()">
                        <div id="discount-info" style="margin-top: 8px; font-size: 13px; color: #d32f2f; font-weight: 600;"></div>
                    </div>
                </div>

                <!-- Quick Set Discount Buttons -->
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 13px; color: #666;">Thiết lập nhanh theo % giảm giá:</label>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="quick-discount-btn" data-discount="10" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-10%</button>
                        <button type="button" class="quick-discount-btn" data-discount="15" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-15%</button>
                        <button type="button" class="quick-discount-btn" data-discount="20" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-20%</button>
                        <button type="button" class="quick-discount-btn" data-discount="25" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-25%</button>
                        <button type="button" class="quick-discount-btn" data-discount="30" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-30%</button>
                        <button type="button" class="quick-discount-btn" data-discount="50" style="padding: 6px 12px; background: #fff; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; font-size: 12px;">-50%</button>
                    </div>
                </div>

                @php
                    $defaultSaleStart = optional($product->sale_starts_at)->format('Y-m-d\TH:i');
                    $defaultSaleEnd = optional($product->sale_ends_at)->format('Y-m-d\TH:i');
                @endphp
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label for="sale_starts_at">Thời gian bắt đầu sale</label>
                        <input type="datetime-local" id="sale_starts_at" name="sale_starts_at" value="{{ old('sale_starts_at', $defaultSaleStart) }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label for="sale_ends_at">Thời gian kết thúc sale</label>
                        <input type="datetime-local" id="sale_ends_at" name="sale_ends_at" value="{{ old('sale_ends_at', $defaultSaleEnd) }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
                <small style="color:#666; display: block; margin-top: 8px;">💡 Để trống một trong hai mốc nếu chương trình không giới hạn theo đầu hoặc cuối.</small>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label for="category_id">Danh mục *</label>
                    <select id="category_id" name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="stock">Số lượng tồn kho *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            </div>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                    Sản phẩm nổi bật
                </label>
                
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_flash_sale" value="1" {{ old('is_flash_sale', $product->is_flash_sale) ? 'checked' : '' }}>
                    Tham gia Flash Sale
                </label>

                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    Đang bán
                </label>
            </div>
            
            <div>
                <button type="submit" class="btn-primary" style="padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-save"></i> Cập nhật sản phẩm
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Tính toán % giảm giá
function calculateDiscount() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
    const discountInfo = document.getElementById('discount-info');
    
    if (price > 0 && salePrice > 0 && salePrice < price) {
        const discount = Math.round(((price - salePrice) / price) * 100);
        const saved = price - salePrice;
        discountInfo.innerHTML = `💰 Giảm <strong>${discount}%</strong> - Tiết kiệm <strong>${saved.toLocaleString('vi-VN')}đ</strong>`;
        discountInfo.style.color = '#d32f2f';
    } else if (salePrice > 0 && salePrice >= price) {
        discountInfo.innerHTML = '⚠️ Giá sale phải nhỏ hơn giá gốc';
        discountInfo.style.color = '#f44336';
    } else {
        discountInfo.innerHTML = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Tính toán ban đầu
    calculateDiscount();
    
    // Quick discount buttons
    document.querySelectorAll('.quick-discount-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const discount = parseFloat(this.dataset.discount);
            const price = parseFloat(document.getElementById('price').value);
            if (price > 0) {
                const salePrice = Math.round(price * (1 - discount / 100));
                document.getElementById('sale_price').value = salePrice;
                calculateDiscount();
                // Tự động check flash sale
                document.querySelector('input[name="is_flash_sale"]').checked = true;
            } else {
                alert('Vui lòng nhập giá gốc trước!');
            }
        });
    });
    
    const setupDynamicGroup = (options) => {
        const { wrapperSelector, addBtnSelector, rowClass, removeSelector, template } = options;
        const wrapper = document.querySelector(wrapperSelector);
        const addBtn = document.querySelector(addBtnSelector);

        if (!wrapper || !addBtn) return;

        const ensureRowStyle = (row) => {
            row.classList.add(rowClass.replace('.', ''));
            row.style.display = 'flex';
            row.style.gap = '10px';
            row.style.marginBottom = '10px';
        };

        const bindRemoveEvents = () => {
            wrapper.querySelectorAll(removeSelector).forEach(button => {
                button.onclick = () => {
                    if (wrapper.children.length === 1) {
                        const newRow = template();
                        ensureRowStyle(newRow);
                        wrapper.replaceChildren(newRow);
                        bindRemoveEvents();
                    } else {
                        button.closest(rowClass).remove();
                    }
                };
            });
        };

        wrapper.querySelectorAll(rowClass).forEach(ensureRowStyle);
        bindRemoveEvents();

        addBtn.addEventListener('click', () => {
            const row = template();
            ensureRowStyle(row);
            wrapper.appendChild(row);
            bindRemoveEvents();
        });
    };

    setupDynamicGroup({
        wrapperSelector: '[data-color-options]',
        addBtnSelector: '[data-add-color]',
        rowClass: '.color-option-row',
        removeSelector: '.remove-color-option',
        template: () => {
            const row = document.createElement('div');
            row.className = 'color-option-row';
            row.innerHTML = `
                <input type="text" name="color_names[]" placeholder="Tên màu (VD: Titan Đen)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <input type="color" name="color_hexes[]" value="#000000" title="Mã màu" style="width:70px; height:42px; border:1px solid #ddd; border-radius:4px; padding:0;">
                <input type="text" name="color_images[]" placeholder="Đường dẫn ảnh (tùy chọn)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <button type="button" class="remove-color-option" title="Xóa" style="background:#f8d7da;border:1px solid #f5c2c7;color:#b02a37;padding:10px 14px;border-radius:4px;cursor:pointer;">×</button>
            `;
            return row;
        }
    });

    setupDynamicGroup({
        wrapperSelector: '[data-size-options]',
        addBtnSelector: '[data-add-size]',
        rowClass: '.size-option-row',
        removeSelector: '.remove-size-option',
        template: () => {
            const row = document.createElement('div');
            row.className = 'size-option-row';
            row.innerHTML = `
                <input type="text" name="size_labels[]" placeholder="Ví dụ: M, L hoặc 128GB" style="flex:1; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <input type="text" name="size_descriptions[]" placeholder="Ghi chú (tùy chọn)" style="flex:2; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <button type="button" class="remove-size-option" title="Xóa" style="background:#f8d7da;border:1px solid #f5c2c7;color:#b02a37;padding:10px 14px;border-radius:4px;cursor:pointer;">×</button>
            `;
            return row;
        }
    });
});
</script>
@endpush
