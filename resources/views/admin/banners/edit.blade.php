@extends('layouts.admin')

@section('title', 'Sửa banner')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Sửa banner</h1>
    <a href="{{ route('banners.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
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
    <form action="{{ route('banners.update', $banner->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="display: grid; gap: 20px;">
            <div>
                <label for="title">Tiêu đề *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $banner->title) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description', $banner->description) }}</textarea>
            </div>
            
            <div>
                <label for="image">Đường dẫn hình ảnh *</label>
                <input type="text" id="image" name="image" value="{{ old('image', $banner->image) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="images/banner/banner1.webp">
            </div>
            
            <div>
                <label for="link">Link</label>
                <input type="url" id="link" name="link" value="{{ old('link', $banner->link) }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="https://example.com">
            </div>
            
            <div>
                <label for="position">Vị trí *</label>
                <input type="number" id="position" name="position" value="{{ old('position', $banner->position) }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    Hoạt động
                </label>
            </div>
            
            <div>
                <button type="submit" class="btn-primary" style="padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-save"></i> Cập nhật banner
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
