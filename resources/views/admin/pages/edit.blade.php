@extends('layouts.admin')

@section('title', 'Sửa trang đơn')

@section('content')
<div class="page-header">
    <h1>Sửa trang đơn</h1>
    <a href="{{ route('pages.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('pages.update', $page) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>Tiêu đề</label><input name="title" value="{{ old('title',$page->title) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>Slug</label><input name="slug" value="{{ old('slug',$page->slug) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div style="grid-column:1/-1;"><label>Nội dung</label><textarea name="content" rows="8" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">{{ old('content',$page->content) }}</textarea></div>
            <div><label><input type="checkbox" name="is_active" value="1" {{ old('is_active',$page->is_active) ? 'checked':'' }}> Hoạt động</label></div>
        </div>
        <div style="margin-top:20px;"><button class="btn-primary" type="submit">Lưu</button></div>
    </form>
</div>
@endsection


