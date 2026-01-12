@extends('layouts.admin')

@section('title', 'Quản lý banners')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-image"></i> Quản lý banners</h1>
    <a href="{{ route('banners.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm banner</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Link</th>
                <th>Vị trí</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td>{{ $banner->title }}</td>
                <td>
                    @if($banner->image)
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" style="width: 60px; height: 40px; object-fit: cover;">
                    @else
                        <span class="badge badge-danger">Không có ảnh</span>
                    @endif
                </td>
                <td>{{ $banner->link ?: '-' }}</td>
                <td>{{ $banner->position }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('banners.toggle', $banner) }}" {{ $banner->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('banners.edit', $banner->id) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;">Chưa có banner nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $banners->links() }}
    </div>
</div>
@endsection
