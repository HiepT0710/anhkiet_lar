@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container" style="padding: 30px 0;">
    <h1 style="margin-bottom:20px;">Danh sách yêu thích</h1>

    @if($items->isEmpty())
        <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
    @else
        <div class="products-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;">
            @foreach($items as $item)
                @php
                    $product = $item->product;
                    if (!$product) continue;
                    $productImages = $product->images;
                    if (is_string($productImages)) {
                        $productImages = json_decode($productImages, true);
                    }
                    $firstImage = is_array($productImages) && count($productImages) > 0 ? $productImages[0] : null;
                @endphp
                <div class="product-card" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    <a href="{{ route('product.show', $product->slug) }}" style="text-decoration:none;color:inherit;">
                        <div class="product-image" style="height:200px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;">
                            @if($firstImage)
                                <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $product->name }}" style="max-width:80%;max-height:80%;object-fit:contain;">
                            @else
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZWVlIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+" alt="{{ $product->name }}" style="max-width:80%;max-height:80%;object-fit:contain;">
                            @endif
                        </div>
                        <div class="product-info" style="padding:14px;">
                            <div class="product-title" style="font-size:14px;font-weight:600;height:40px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $product->name }}</div>
                            <div class="product-price" style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                                <span class="price-current" style="font-size:16px;font-weight:700;color:#d32f2f;">{{ number_format($product->final_price) }}đ</span>
                                @if($product->is_sale_active)
                                    <span class="price-old" style="font-size:13px;color:#999;text-decoration:line-through;">{{ number_format($product->price) }}đ</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" style="padding:0 14px 12px;">
                        @csrf
                        <button type="submit" style="border:none;background:#fee2e2;color:#b91c1c;border-radius:6px;padding:6px 10px;font-size:13px;cursor:pointer;width:100%;">Xóa khỏi yêu thích</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection


