@extends('layouts.app')

@section('title', 'Tất cả danh mục - AnhKiet Store')

@push('styles')
<style>
    .categories-container {
        margin: 40px 0;
    }
    .page-header {
        margin-bottom: 40px;
    }
    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .category-card {
        background: #fff;
        padding: 30px 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        text-decoration: none;
        display: block;
        color: #333;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .category-icon {
        font-size: 60px;
        color: #d32f2f;
        margin-bottom: 15px;
    }
    .category-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .category-description {
        font-size: 14px;
        color: #666;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="categories-container">
        <div class="page-header">
            <h1>Tất cả danh mục sản phẩm</h1>
            <p>Tìm kiếm sản phẩm theo danh mục</p>
        </div>
        
        <div class="categories-grid">
            @forelse($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="category-card">
                <div class="category-icon">
                    <i class="{{ $category->icon ?? 'fas fa-box' }}"></i>
                </div>
                <div class="category-name">{{ $category->name }}</div>
                @if($category->description)
                <div class="category-description">{{ $category->description }}</div>
                @endif
            </a>
            @empty
            <p>Chưa có danh mục nào.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

