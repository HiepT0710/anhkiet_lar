@extends('layouts.admin')

@section('title', 'Sửa menu')

@section('content')
<div class="page-header">
    <h1>Sửa menu</h1>
    <a href="{{ route('menus.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('menus.update', $item) }}" method="POST">@csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>Tiêu đề</label><input name="title" value="{{ old('title',$item->title) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>URL</label><input name="url" value="{{ old('url',$item->url) }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>Cha</label>
                <select name="parent_id" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
                    <option value="">-- Không --</option>
                    @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ $item->parent_id==$p->id?'selected':'' }}>{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Thứ tự</label><input type="number" min="0" name="order" value="{{ old('order',$item->order) }}" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label><input type="checkbox" name="is_active" value="1" {{ old('is_active',$item->is_active)?'checked':'' }}> Hoạt động</label></div>
        </div>
        <div style="margin-top:20px;"><button class="btn-primary" type="submit">Lưu</button></div>
    </form>
</div>
@endsection


