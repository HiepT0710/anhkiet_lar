@extends('layouts.admin')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-eye"></i> Chi tiết sản phẩm</h1>
    <a href="{{ route('products.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <div style="display: grid; gap: 20px;">
        <div>
            <h3>Thông tin cơ bản</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 200px;">ID:</td>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tên sản phẩm:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Slug:</td>
                    <td>{{ $product->slug }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Danh mục:</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Giá:</td>
                    <td>{{ number_format($product->price) }}đ</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Giá sale:</td>
                    <td>{{ $product->sale_price ? number_format($product->sale_price) . 'đ' : '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Số lượng tồn kho:</td>
                    <td>{{ $product->stock }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sản phẩm nổi bật:</td>
                    <td>
                        @if($product->featured)
                            <span class="badge badge-success">Có</span>
                        @else
                            <span class="badge badge-danger">Không</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Trạng thái:</td>
                    <td>
                        @if($product->is_active)
                            <span class="badge badge-success">Đang bán</span>
                        @else
                            <span class="badge badge-danger">Tạm ngưng</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày tạo:</td>
                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày cập nhật:</td>
                    <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
        
        @if($product->description)
        <div>
            <h3>Mô tả</h3>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 4px;">
                {{ $product->description }}
            </div>
        </div>
        @endif
        
        @if($product->images)
        <div>
            <h3>Hình ảnh sản phẩm</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @foreach(json_decode($product->images) as $image)
                    <img src="{{ asset('storage/' . $image) }}" alt="Product Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                @endforeach
            </div>
        </div>
        @endif
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('products.edit', $product->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Sửa sản phẩm
            </a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash"></i> Xóa sản phẩm
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
