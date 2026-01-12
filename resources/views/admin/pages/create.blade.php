@extends('layouts.admin')

@section('title', 'Thêm trang đơn')

@section('content')
<div class="page-header">
    <h1>Thêm trang đơn</h1>
    <a href="{{ route('pages.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('pages.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>Tiêu đề</label><input name="title" value="{{ old('title') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>Slug</label><input name="slug" value="{{ old('slug') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div style="grid-column:1/-1;"><label>Nội dung</label><textarea name="content" rows="8" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">{{ old('content') }}</textarea></div>
            <div><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked':'' }}> Hoạt động</label></div>
        </div>
        <div style="margin-top:20px;"><button class="btn-primary" type="submit">Lưu</button></div>
    </form>
</div>
@endsection


