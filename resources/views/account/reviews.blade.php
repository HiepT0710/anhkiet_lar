@extends('layouts.app')

@section('title', 'Đánh giá của tôi')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        @include('account.sidebar')

        <div>
            <h1 style="margin-bottom: 30px; color: #2c3e50;">Đánh giá của tôi</h1>

            @if($reviews->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach($reviews as $review)
                <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div style="flex: 1;">
                            @if($review->product)
                            <h3 style="margin: 0 0 10px 0; font-size: 16px;">
                                <a href="{{ route('product.show', $review->product->slug) }}" style="color: #2563eb; text-decoration: none;">
                                    {{ $review->product->name }}
                                </a>
                            </h3>
                            @else
                            <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #999;">Sản phẩm đã bị xóa</h3>
                            @endif
                            <div style="display: flex; gap: 3px; margin-bottom: 10px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#ffc107' : '#ddd' }}; font-size: 16px;"></i>
                                @endfor
                                <span style="margin-left: 8px; font-weight: 600;">{{ $review->rating }}/5</span>
                            </div>
                            @if($review->comment)
                            <div style="color: #555; line-height: 1.6; margin-bottom: 10px;">
                                {{ $review->comment }}
                            </div>
                            @endif
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 12px; color: #999; margin-bottom: 5px;">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($review->is_approved)
                            <span style="padding: 5px 12px; border-radius: 20px; background: #e8f5e9; color: #388e3c; font-weight: 600; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Đã duyệt
                            </span>
                            @else
                            <span style="padding: 5px 12px; border-radius: 20px; background: #fff3e0; color: #f57c00; font-weight: 600; font-size: 12px;">
                                <i class="fas fa-clock"></i> Chờ duyệt
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top: 20px;">
                {{ $reviews->links() }}
            </div>
            @else
            <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center;">
                <i class="fas fa-star" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                <p style="color: #999;">Bạn chưa có đánh giá nào</p>
                <a href="{{ route('home') }}" style="display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: none; font-weight: 600;">
                    Xem sản phẩm <i class="fas fa-chevron-right"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

