<?php

namespace App\Services;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class FeaturedProductService
{
    /**
     * Cập nhật sản phẩm nổi bật dựa trên top sản phẩm bán chạy
     * 
     * @param int $limit Số lượng sản phẩm nổi bật (mặc định: 8)
     * @return array Thông tin về các sản phẩm đã được đánh dấu
     */
    public function updateFeaturedProducts(int $limit = 8): array
    {
        // Lấy top sản phẩm bán chạy từ các đơn hàng đã hoàn thành
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['completed', 'shipping', 'processing']);
            })
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->pluck('product_id')
            ->toArray();

        // Nếu không có đơn hàng nào, lấy sản phẩm mới nhất
        if (empty($topProducts)) {
            $topProducts = Product::where('is_active', true)
                ->latest()
                ->limit($limit)
                ->pluck('id')
                ->toArray();
        }

        // Reset tất cả sản phẩm về không nổi bật
        Product::where('featured', true)->update(['featured' => false]);

        // Đánh dấu top sản phẩm bán chạy là nổi bật
        Product::whereIn('id', $topProducts)
            ->where('is_active', true)
            ->update(['featured' => true]);

        // Lấy thông tin các sản phẩm đã được đánh dấu
        $featuredProducts = Product::whereIn('id', $topProducts)
            ->where('featured', true)
            ->get();

        return [
            'count' => $featuredProducts->count(),
            'products' => $featuredProducts->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                ];
            })->toArray(),
        ];
    }

    /**
     * Lấy số lượng đã bán của một sản phẩm
     */
    public function getProductSalesCount(int $productId): int
    {
        return OrderItem::where('product_id', $productId)
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['completed', 'shipping', 'processing']);
            })
            ->sum('quantity');
    }
}

