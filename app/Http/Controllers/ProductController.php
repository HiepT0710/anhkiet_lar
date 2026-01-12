<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $placeholderImage = "data:image/svg+xml;base64," . base64_encode('<svg width="500" height="500" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#eee"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="16" fill="#999" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') . '</text></svg>');

        $productImages = $product->images;
        if (is_string($productImages)) {
            $productImages = json_decode($productImages, true);
        }

        $normalizedImages = collect($productImages ?? [])->filter()->map(function ($imagePath) {
            return Str::startsWith($imagePath, ['http://', 'https://'])
                ? $imagePath
                : asset('storage/' . ltrim($imagePath, '/'));
        });

        $predefinedGallery = collect(config("product_gallery.{$product->slug}", []))
            ->filter()
            ->map(function ($imagePath) {
                return Str::startsWith($imagePath, ['http://', 'https://'])
                    ? $imagePath
                    : asset('storage/' . ltrim($imagePath, '/'));
            });

        if ($predefinedGallery->isNotEmpty()) {
            $normalizedImages = $normalizedImages->merge(
                $predefinedGallery->reject(fn ($imageUrl) => $normalizedImages->contains($imageUrl))
            );
        }

        if ($normalizedImages->isEmpty()) {
            $normalizedImages->push($placeholderImage);
        }

        $productImages = $normalizedImages->values()->all();
        $firstImage = $productImages[0] ?? $placeholderImage;

        $colorOptions = [];
        if (is_array($product->color_options)) {
            foreach ($product->color_options as $option) {
                if (!empty($option['name'])) {
                    $colorOptions[] = [
                        'name' => $option['name'],
                        'hex' => $option['hex'] ?? null,
                        'image' => $option['image'] ?? null,
                    ];
                }
            }
        }

        // Xây dựng danh sách phiên bản (dung lượng) kèm chênh lệch giá nếu có
        $sizeOptions = [];
        $baseFinalPrice = (float) $product->final_price;
        $baseListPrice = (float) $product->price;

        $parseSizeOptions = function (array $rawOptions) use (&$sizeOptions, $baseFinalPrice, $baseListPrice) {
            foreach ($rawOptions as $option) {
                if (empty($option['label'])) {
                    continue;
                }

                $description = $option['description'] ?? null;
                $delta = 0;

                if ($description) {
                    // Tìm mẫu +2.000.000đ hoặc -1.000.000đ trong mô tả để suy ra chênh lệch giá
                    if (preg_match('/([+\-])\s*([\d\.]+)\s*đ/u', $description, $m)) {
                        $sign = $m[1] === '-' ? -1 : 1;
                        $amount = (int) str_replace('.', '', $m[2]);
                        $delta = $sign * $amount;
                    }
                }

                    $sizeOptions[] = [
                        'label' => $option['label'],
                    'description' => $description,
                    'final_price' => $baseFinalPrice + $delta,
                    'list_price' => $baseListPrice + $delta,
                    ];
            }
        };

        if (is_array($product->size_options)) {
            $parseSizeOptions($product->size_options);
        }

        if (empty($sizeOptions)) {
            $predefinedVersions = config('product_versions');
            $configOptions = $predefinedVersions[$product->slug] ?? [];
            if (is_array($configOptions)) {
                $parseSizeOptions($configOptions);
            }
        }

        $defaultImageUrl = $firstImage ?? $placeholderImage;

        // Lấy đánh giá đã được duyệt của sản phẩm
        $reviews = Review::where('product_id', $product->id)
            ->approved()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        // Tính điểm trung bình
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('product', compact(
            'product',
            'relatedProducts',
            'colorOptions',
            'sizeOptions',
            'defaultImageUrl',
            'reviews',
            'averageRating',
            'totalReviews'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
