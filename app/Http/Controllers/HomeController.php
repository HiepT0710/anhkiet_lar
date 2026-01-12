<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\SearchHistory;
use App\Models\Brand;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('position')
            ->get();

        $sidebarCategories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->take(8)
            ->get();

        // Lấy sản phẩm nổi bật, ưu tiên top bán chạy
        $topSoldProductIds = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['completed', 'shipping', 'processing']);
            })
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->pluck('product_id')
            ->toArray();

        $featuredQuery = Product::where('is_active', true)
            ->where('featured', true);

        if (!empty($topSoldProductIds)) {
            // Sắp xếp theo thứ tự trong top bán chạy
            $featuredQuery->orderByRaw('FIELD(id, ' . implode(',', $topSoldProductIds) . ') DESC')
                          ->orderByDesc('created_at');
        } else {
            // Nếu không có top bán chạy, sắp xếp theo ngày tạo
            $featuredQuery->orderByDesc('created_at');
        }

        $featuredProducts = $featuredQuery->take(8)->get();

        $latestProducts = Product::where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        $brands = Brand::where('is_active', true)
            ->orderBy('name')
            ->take(20)
            ->get();

        $flashSaleProducts = Product::activeFlashSale()
            ->with('category')
            ->orderBy('sale_ends_at')
            ->take(8)
            ->get();

        $nextSaleProduct = Product::where('is_active', true)
            ->where('is_flash_sale', true)
            ->whereNotNull('sale_starts_at')
            ->where('sale_starts_at', '>', now())
            ->orderBy('sale_starts_at')
            ->first();

        $countdownTarget = $flashSaleProducts->pluck('sale_ends_at')->filter()->min()
            ?? $nextSaleProduct?->sale_starts_at;

        $flashSaleCountdown = $this->buildCountdown($countdownTarget);

        // Lấy đánh giá đã được duyệt để hiển thị trên trang chủ
        $reviews = Review::approved()
            ->with(['product', 'user'])
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('home', compact(
            'banners',
            'categories',
            'sidebarCategories',
            'featuredProducts',
            'latestProducts',
            'flashSaleProducts',
            'flashSaleCountdown',
            'nextSaleProduct',
            'brands',
            'reviews'
        ));
    }

    public function search(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        $searchTerm = null;

        if ($request->has('q') && $request->q) {
            $searchTerm = trim($request->q);

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            });

            // Lưu lịch sử tìm kiếm cho user đang đăng nhập
            if ($searchTerm !== '') {
                $userId = auth()->id();

                if ($userId) {
                    SearchHistory::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'keyword' => $searchTerm,
                        ],
                        [
                            'last_searched_at' => now(),
                        ]
                    )->increment('search_count');
                }
            }
        }

        // Lọc theo danh mục (từ mega menu)
        if ($request->filled('category')) {
            $categorySlug = $request->get('category');
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Lọc theo khoảng giá
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        // Lọc theo trạng thái khuyến mãi
        if ($request->get('on_sale') === '1') {
            $query->where('is_flash_sale', true);
        }

        // Sắp xếp
        $sort = $request->get('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest('id');
                break;
            case 'bestseller':
                $query->orderByDesc('featured')->latest('id');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        // Lịch sử tìm kiếm gần đây của user
        $recentSearches = collect();
        $userId = auth()->id();
        if ($userId) {
            $recentSearches = SearchHistory::where('user_id', $userId)
                ->orderByDesc('last_searched_at')
                ->limit(5)
                ->get();
        }

        // Gợi ý sản phẩm: sản phẩm nổi bật hoặc mới nhất
        $suggestedProducts = Product::where('is_active', true)
            ->orderByDesc('featured')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('search', compact('products', 'categories', 'recentSearches', 'suggestedProducts'));
    }

    public function searchSuggestions(Request $request)
    {
        $term = trim((string) $request->get('q', ''));
        $userId = auth()->id();

        $recentSearches = [];
        if ($userId) {
            $recentSearches = SearchHistory::where('user_id', $userId)
                ->when($term !== '', function ($q) use ($term) {
                    $q->where('keyword', 'like', '%' . $term . '%');
                })
                ->orderByDesc('last_searched_at')
                ->limit(5)
                ->pluck('keyword')
                ->toArray();
        }

        $productSuggestions = Product::where('is_active', true)
            ->when($term !== '', function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%');
            })
            ->orderByDesc('featured')
            ->orderByDesc('id')
            ->limit(8)
            ->get(['name', 'slug']);

        return response()->json([
            'recent_searches' => $recentSearches,
            'products' => $productSuggestions,
        ]);
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
        ];
    }
}
