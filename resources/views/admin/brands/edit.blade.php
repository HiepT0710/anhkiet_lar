@extends('layouts.admin')

@section('title', 'Sửa thương hiệu')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tags"></i> Sửa thương hiệu</h1>
    <a href="{{ route('brands.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('brands.update', $brand) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
                <label>Tên</label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $brand->slug) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Logo (URL)</label>
                <input type="text" name="logo" value="{{ old('logo', $brand->logo) }}" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Thứ tự</label>
                <input type="number" name="order" value="{{ old('order', $brand->order) }}" min="0" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div style="grid-column:1/-1;">
                <label>Mô tả</label>
                <textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">{{ old('description', $brand->description) }}</textarea>
            </div>
            <div>
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}> Hoạt động</label>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection


