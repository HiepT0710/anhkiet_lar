@extends('layouts.admin')

@section('title', 'Thêm danh mục')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Thêm danh mục</h1>
    <a href="{{ route('categories.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
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
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div style="display: grid; gap: 20px;">
            <div>
                <label for="name">Tên danh mục *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="slug">Slug *</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label for="description">Mô tả</label>
                <textarea id="description" name="description" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ old('description') }}</textarea>
            </div>
            
            <div>
                <label for="image">Hình ảnh</label>
                <input type="text" id="image" name="image" value="{{ old('image') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Đường dẫn hình ảnh">
            </div>
            
            <div>
                <label for="icon">Icon</label>
                <input type="text" id="icon" name="icon" value="{{ old('icon') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="fas fa-mobile-alt">
            </div>
            
            <div>
                <label for="order">Thứ tự *</label>
                <input type="number" id="order" name="order" value="{{ old('order', 0) }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    Hoạt động
                </label>
            </div>
            
            <div>
                <button type="submit" class="btn-primary" style="padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-save"></i> Lưu danh mục
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
