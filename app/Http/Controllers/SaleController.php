<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'active'); // active, upcoming, ended
        $now = now();

        // Query cơ bản cho sản phẩm sale
        $query = Product::where('is_active', true)
            ->where('is_flash_sale', true)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->with('category');

        // Xử lý theo tab
        switch ($tab) {
            case 'upcoming':
                // Sắp sale: sale_starts_at trong tương lai
                $query->whereNotNull('sale_starts_at')
                      ->where('sale_starts_at', '>', $now)
                      ->orderBy('sale_starts_at', 'asc');
                break;
            
            case 'ended':
                // Đã kết thúc: sale_ends_at trong quá khứ
                $query->whereNotNull('sale_ends_at')
                      ->where('sale_ends_at', '<', $now)
                      ->orderBy('sale_ends_at', 'desc');
                break;
            
            case 'active':
            default:
                // Đang sale: đang trong thời gian sale
                $query->where(function ($q) use ($now) {
                    $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
                })
                ->orderBy('sale_ends_at', 'asc'); // Sắp xếp theo thời gian kết thúc
                break;
        }

        // Filter theo danh mục
        if ($request->filled('category')) {
            $categorySlug = $request->get('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter theo khoảng giá
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('sale_price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('sale_price', '<=', (float) $maxPrice);
        }

        // Filter theo % giảm giá
        $minDiscount = $request->get('min_discount');
        if ($minDiscount !== null && $minDiscount !== '') {
            $query->whereRaw('((price - sale_price) / price * 100) >= ?', [(float) $minDiscount]);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'ending_soon');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'discount_desc':
                $query->orderByRaw('((price - sale_price) / price * 100) DESC');
                break;
            case 'discount_asc':
                $query->orderByRaw('((price - sale_price) / price * 100) ASC');
                break;
            case 'ending_soon':
                if ($tab === 'active') {
                    $query->orderBy('sale_ends_at', 'asc');
                } else {
                    $query->orderBy('sale_starts_at', 'asc');
                }
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            default:
                if ($tab === 'active') {
                    $query->orderBy('sale_ends_at', 'asc');
                }
                break;
        }

        $products = $query->paginate(20)->withQueryString();

        // Lấy danh sách danh mục để filter
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Tính toán countdown cho từng sản phẩm
        $products->getCollection()->transform(function ($product) use ($tab) {
            if ($tab === 'active' && $product->sale_ends_at) {
                $product->countdown = $this->buildCountdown($product->sale_ends_at);
            } elseif ($tab === 'upcoming' && $product->sale_starts_at) {
                $product->countdown = $this->buildCountdown($product->sale_starts_at);
            } else {
                $product->countdown = null;
            }
            return $product;
        });

        // Thống kê nhanh
        $stats = [
            'active_count' => Product::where('is_active', true)
                ->where('is_flash_sale', true)
                ->whereNotNull('sale_price')
                ->whereColumn('sale_price', '<', 'price')
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
                })
                ->count(),
            'upcoming_count' => Product::where('is_active', true)
                ->where('is_flash_sale', true)
                ->whereNotNull('sale_starts_at')
                ->where('sale_starts_at', '>', $now)
                ->count(),
            'ended_count' => Product::where('is_active', true)
                ->where('is_flash_sale', true)
                ->whereNotNull('sale_ends_at')
                ->where('sale_ends_at', '<', $now)
                ->count(),
        ];

        return view('sale', compact('products', 'categories', 'tab', 'stats'));
    }

    private function buildCountdown(?Carbon $target): ?array
    {
        if (!$target) {
            return null;
        }

        $now = now();
        $seconds = $now->diffInSeconds($target, false);

        if ($seconds < 0) {
            $seconds = 0;
        }

        $days = intdiv($seconds, 86400);
        $seconds -= $days * 86400;

        $hours = intdiv($seconds, 3600);
        $seconds -= $hours * 3600;

        $minutes = intdiv($seconds, 60);
        $seconds -= $minutes * 60;

        return [
            'days' => str_pad($days, 2, '0', STR_PAD_LEFT),
            'hours' => str_pad($hours, 2, '0', STR_PAD_LEFT),
            'minutes' => str_pad($minutes, 2, '0', STR_PAD_LEFT),
            'seconds' => str_pad($seconds, 2, '0', STR_PAD_LEFT),
            'total_seconds' => $now->diffInSeconds($target, false),
        ];
    }
}
