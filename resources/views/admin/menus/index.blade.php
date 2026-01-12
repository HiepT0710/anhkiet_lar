@extends('layouts.admin')

@section('title', 'Quản lý menu')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-bars"></i> Quản lý menu</h1>
    <a href="{{ route('menus.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm menu</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead><tr><th>ID</th><th>Tiêu đề</th><th>URL</th><th>Cha</th><th>Thứ tự</th><th>TT</th><th>Thao tác</th></tr></thead>
        <tbody>
        @foreach($menuItems as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->title }}</td>
            <td>{{ $item->url }}</td>
            <td>{{ $item->parent_id ?: '-' }}</td>
            <td>{{ $item->order }}</td>
            <td>
                <label class="switch">
                    <input type="checkbox" class="toggle-status" data-url="{{ route('menus.toggle', $item) }}" {{ $item->is_active ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </td>
            <td>
                <a href="{{ route('menus.edit', $item) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('menus.destroy', $item) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                    <button class="btn-delete" onclick="return confirm('Xóa menu này?')"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $menuItems->links() }}</div>
</div>
@endsection


