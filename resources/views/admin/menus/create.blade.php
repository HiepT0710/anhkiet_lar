@extends('layouts.admin')

@section('title', 'Thêm menu')

@section('content')
<div class="page-header">
    <h1>Thêm menu</h1>
    <a href="{{ route('menus.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <form action="{{ route('menus.store') }}" method="POST">@csrf
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div><label>Tiêu đề</label><input name="title" value="{{ old('title') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>URL</label><input name="url" value="{{ old('url') }}" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label>Cha</label>
                <select name="parent_id" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;">
                    <option value="">-- Không --</option>
                    @foreach($parents as $p)
                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Thứ tự</label><input type="number" min="0" name="order" value="{{ old('order',0) }}" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;"></div>
            <div><label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true)?'checked':'' }}> Hoạt động</label></div>
        </div>
        <div style="margin-top:20px;"><button class="btn-primary" type="submit">Lưu</button></div>
    </form>
</div>
@endsection


