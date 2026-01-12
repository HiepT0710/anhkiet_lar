@extends('layouts.admin')

@section('title', 'Trang đơn')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file"></i> Trang đơn</h1>
    <a href="{{ route('pages.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm trang</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead><tr><th>ID</th><th>Tiêu đề</th><th>Slug</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td>{{ $page->title }}</td>
                <td>{{ $page->slug }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('pages.toggle', $page) }}" {{ $page->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('pages.edit', $page) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('pages.destroy', $page) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                        <button class="btn-delete" onclick="return confirm('Xóa trang này?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $pages->links() }}</div>
</div>
@endsection


