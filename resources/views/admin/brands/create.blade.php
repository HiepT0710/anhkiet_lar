@extends('layouts.admin')

@section('title', 'Thêm thương hiệu')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tags"></i> Thêm thương hiệu</h1>
    <a href="{{ route('brands.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('brands.store') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
                <label>Tên</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Logo (URL)</label>
                <input type="text" name="logo" value="{{ old('logo') }}" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Thứ tự</label>
                <input type="number" name="order" value="{{ old('order',0) }}" min="0" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div style="grid-column:1/-1;">
                <label>Mô tả</label>
                <textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">{{ old('description') }}</textarea>
            </div>
            <div>
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Hoạt động</label>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </div>
    </form>
</div>
@endsection


