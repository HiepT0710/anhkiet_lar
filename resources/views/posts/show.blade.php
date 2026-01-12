@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container" style="padding:25px 0;">
    <div style="max-width:920px;margin:0 auto;">
        <div style="margin-bottom:12px;">
            <a href="{{ route('posts.index') }}">Tin tức</a> >
            <a href="{{ route('posts.category', $post->category->slug) }}">{{ $post->category->name }}</a>
        </div>
        <h1 style="margin-bottom:10px;">{{ $post->title }}</h1>
        <div style="color:#888;font-size:13px;margin-bottom:16px;">
            {{ $post->published_at? $post->published_at->format('d/m/Y H:i') : '' }}
        </div>
        @if($post->image)
        <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" style="width:100%;max-height:420px;object-fit:cover;border-radius:8px;margin-bottom:16px;">
        @endif
        <article style="line-height:1.8;color:#333;">
            {!! $post->content !!}
        </article>

        @if($related->count())
        <div style="margin-top:40px;">
            <h3>Bài viết liên quan</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-top:12px;">
                @foreach($related as $item)
                <a href="{{ route('posts.show',$item->slug) }}" style="text-decoration:none;color:#333;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,.06);">
                    <div style="height:130px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;">
                        <img src="{{ $item->image ? asset($item->image) : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjEzMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+' }}" alt="{{ $item->title }}" style="max-width:100%;max-height:100%;object-fit:cover;">
                    </div>
                    <div style="padding:10px 12px;">
                        <div style="font-weight:600;font-size:14px;line-height:1.4;">{{ $item->title }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


