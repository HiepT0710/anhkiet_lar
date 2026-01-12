@extends('layouts.admin')

@section('title', 'Thêm chuyên mục')

@section('content')
<div class="page-header">
    <h1>Thêm chuyên mục</h1>
    <a href="{{ route('post-categories.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('post-categories.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div>
                <label>Tên</label>
                <input name="name" value="{{ old('name') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label>Slug</label>
                <input name="slug" value="{{ old('slug') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
            </div>
            <div>
                <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Hoạt động</label>
            </div>
        </div>
        <div style="margin-top:20px;"><button class="btn-primary" type="submit">Lưu</button></div>
    </form>
</div>
@endsection


