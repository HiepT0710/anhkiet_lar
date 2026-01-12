@extends('layouts.admin')

@section('title', 'Mã giảm giá')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-ticket"></i> Mã giảm giá</h1>
    <a href="{{ route('coupons.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm mã</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-section">
    <table>
        <thead>
        <tr>
            <th>Code</th><th>Kiểu</th><th>Giá trị</th><th>Thời gian</th><th>Giới hạn</th><th>TT</th><th>Thao tác</th>
        </tr>
        </thead>
        <tbody>
        @foreach($coupons as $c)
        <tr>
            <td>{{ $c->code }}</td>
            <td>{{ $c->type }}</td>
            <td>{{ $c->value }}</td>
            <td>{{ $c->start_at? $c->start_at->format('d/m') : '-' }} - {{ $c->end_at? $c->end_at->format('d/m') : '-' }}</td>
            <td>{{ $c->used_count }}/{{ $c->usage_limit ?: '∞' }}</td>
            <td>
                <label class="switch">
                    <input type="checkbox" class="toggle-status" data-url="{{ route('coupons.toggle', $c) }}" {{ $c->is_active ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </td>
            <td>
                <a href="{{ route('coupons.edit', $c) }}" class="btn-edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('coupons.destroy', $c) }}" method="POST" style="display:inline;">@csrf @method('DELETE')
                    <button class="btn-delete" onclick="return confirm('Xóa mã này?')"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    <div class="pagination">{{ $coupons->links() }}</div>
</div>
@endsection


