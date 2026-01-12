@extends('layouts.admin')

@section('title', 'Chuyên mục bài viết')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-folder"></i> Chuyên mục bài viết</h1>
    <a href="{{ route('post-categories.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm chuyên mục</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
        <tr>
            <th>ID</th><th>Tên</th><th>Slug</th><th>Trạng thái</th><th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('post-categories.toggle', $category) }}" {{ $category->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('post-categories.edit', $category) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('post-categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Xóa chuyên mục này?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $categories->links() }}</div>
</div>
@endsection


