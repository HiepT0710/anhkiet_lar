@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-newspaper"></i> Quản lý bài viết</h1>
    <a href="{{ route('posts.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm bài viết</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
        <tr>
            <th>ID</th><th>Tiêu đề</th><th>Chuyên mục</th><th>Xuất bản</th><th>Trạng thái</th><th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->category->name ?? '-' }}</td>
                <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('posts.toggle', $post) }}" {{ $post->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('posts.edit', $post) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Xóa bài viết này?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $posts->links() }}</div>
</div>
@endsection


