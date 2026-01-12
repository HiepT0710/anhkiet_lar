@extends('layouts.admin')

@section('title', 'Chi tiết banner')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-eye"></i> Chi tiết banner</h1>
    <a href="{{ route('banners.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <div style="display: grid; gap: 20px;">
        <div>
            <h3>Thông tin cơ bản</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 200px;">ID:</td>
                    <td>{{ $banner->id }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tiêu đề:</td>
                    <td>{{ $banner->title }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Vị trí:</td>
                    <td>{{ $banner->position }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Link:</td>
                    <td>{{ $banner->link ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Hình ảnh:</td>
                    <td>{{ $banner->image ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Trạng thái:</td>
                    <td>
                        @if($banner->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-danger">Tạm ngưng</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày tạo:</td>
                    <td>{{ $banner->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày cập nhật:</td>
                    <td>{{ $banner->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
        
        @if($banner->description)
        <div>
            <h3>Mô tả</h3>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 4px;">
                {{ $banner->description }}
            </div>
        </div>
        @endif
        
        @if($banner->image)
        <div>
            <h3>Hình ảnh</h3>
            <div style="text-align: center;">
                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" style="max-width: 100%; height: auto; border-radius: 8px;">
            </div>
        </div>
        @endif
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('banners.edit', $banner->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Sửa banner
            </a>
            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash"></i> Xóa banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
