@extends('layouts.admin')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-eye"></i> Chi tiết danh mục</h1>
    <a href="{{ route('categories.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <div style="display: grid; gap: 20px;">
        <div>
            <h3>Thông tin cơ bản</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 200px;">ID:</td>
                    <td>{{ $category->id }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tên danh mục:</td>
                    <td>{{ $category->name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Slug:</td>
                    <td>{{ $category->slug }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Thứ tự:</td>
                    <td>{{ $category->order }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Icon:</td>
                    <td>{{ $category->icon ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Hình ảnh:</td>
                    <td>{{ $category->image ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Trạng thái:</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Tạm ngưng</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày tạo:</td>
                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày cập nhật:</td>
                    <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
        
        @if($category->description)
        <div>
            <h3>Mô tả</h3>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 4px;">
                {{ $category->description }}
            </div>
        </div>
        @endif
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('categories.edit', $category->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Sửa danh mục
            </a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash"></i> Xóa danh mục
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
