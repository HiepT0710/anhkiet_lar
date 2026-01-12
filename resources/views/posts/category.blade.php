@extends('layouts.app')

@section('title', 'Chuyên mục: ' . $category->name)

@section('content')
<div class="container" style="padding: 25px 0;">
    <div style="display:flex;gap:30px;align-items:flex-start;">
        <aside style="width:260px;">
            <h3 style="margin-bottom:10px;">Theo danh mục tin</h3>
            <ul style="list-style:none;padding-left:0;">
                @foreach($categories as $cat)
                <li style="margin-bottom:8px;"><a href="{{ route('posts.category',$cat->slug) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </aside>
        <main style="flex:1;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                <h1>{{ $category->name }}</h1>
                <a href="{{ route('posts.index') }}">Xem tất cả bài viết</a>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
                @foreach($posts as $post)
                <a href="{{ route('posts.show',$post->slug) }}" style="background:#fff;border-radius:8px;overflow:hidden;text-decoration:none;color:#333;box-shadow:0 2px 6px rgba(0,0,0,.06);">
                    <div style="height:160px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;">
                        <img src="{{ $post->image ? asset($post->image) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjE2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+'" alt="{{ $post->title }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                    </div>
                    <div style="padding:12px 14px;">
                        <h3 style="font-size:16px;line-height:1.4;margin-bottom:8px;">{{ $post->title }}</h3>
                        <p style="color:#666;font-size:13px;">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 100) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="margin-top:20px;">{{ $posts->links() }}</div>
        </main>
    </div>
</div>
@endsection


