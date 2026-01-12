@extends('layouts.admin')

@section('title', 'Thêm banner')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Thêm banner</h1>
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
    <form action="{{ route('banners.store') }}" method="POST">
        @csrf
        <div style="display: grid; gap: 20px;">
            <div>
                <label for="title">Tiêu đề *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description') }}</textarea>
            </div>
            
            <div>
                <label for="image">Đường dẫn hình ảnh *</label>
                <input type="text" id="image" name="image" value="{{ old('image') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="images/banner/banner1.webp">
            </div>
            
            <div>
                <label for="link">Link</label>
                <input type="url" id="link" name="link" value="{{ old('link') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="https://example.com">
            </div>
            
            <div>
                <label for="position">Vị trí *</label>
                <input type="number" id="position" name="position" value="{{ old('position', 1) }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    Hoạt động
                </label>
            </div>
            
            <div>
                <button type="submit" class="btn-primary" style="padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-save"></i> Lưu banner
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
