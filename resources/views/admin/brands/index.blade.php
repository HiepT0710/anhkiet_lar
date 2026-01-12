@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tags"></i> Quản lý thương hiệu</h1>
    <a href="{{ route('brands.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm thương hiệu</a>
    </div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Thứ tự</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>
                    @if($brand->logo)
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="width:50px;height:34px;object-fit:contain;">
                    @else
                        <span class="badge badge-danger">Không logo</span>
                    @endif
                </td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->slug }}</td>
                <td>{{ $brand->order }}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-status" data-url="{{ route('brands.toggle', $brand) }}" {{ $brand->is_active ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </td>
                <td>
                    <a href="{{ route('brands.edit', $brand) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Xóa thương hiệu này?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Chưa có thương hiệu</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination">{{ $brands->links() }}</div>
</div>
@endsection


