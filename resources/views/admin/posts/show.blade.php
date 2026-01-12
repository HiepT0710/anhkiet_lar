@extends('layouts.admin')

@section('title', 'Chi tiết bài viết')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-eye"></i> Chi tiết bài viết</h1>
    <a href="{{ route('posts.admin.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="table-section">
    <div style="display: grid; gap: 20px;">
        <div>
            <h3>Thông tin cơ bản</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="font-weight: bold; width: 200px;">ID:</td>
                    <td>{{ $post->id }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Tiêu đề:</td>
                    <td>{{ $post->title }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Slug:</td>
                    <td>{{ $post->slug }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Danh mục:</td>
                    <td>{{ $post->category->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Trạng thái:</td>
                    <td>
                        @if($post->is_active)
                            <span class="badge badge-success">Đang hiển thị</span>
                        @else
                            <span class="badge badge-danger">Ẩn</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày đăng:</td>
                    <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Ngày tạo:</td>
                    <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        @if($post->excerpt)
        <div>
            <h3>Tóm tắt</h3>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 4px;">
                {{ $post->excerpt }}
            </div>
        </div>
        @endif

        @if($post->content)
        <div>
            <h3>Nội dung</h3>
            <div style="padding: 15px; background: #fff; border-radius: 4px;">
                {!! $post->content !!}
            </div>
        </div>
        @endif

        @if($post->image)
        <div>
            <h3>Hình ảnh</h3>
            <div>
                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" style="max-width: 320px; border-radius: 6px; border: 1px solid #ddd;">
            </div>
        </div>
        @endif

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('posts.edit', $post->id) }}" class="btn-primary">
                <i class="fas fa-edit"></i> Sửa bài viết
            </a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                    <i class="fas fa-trash"></i> Xóa bài viết
                </button>
            </form>
        </div>
    </div>
</div>
@endsection


