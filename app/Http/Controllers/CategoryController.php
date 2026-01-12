<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('categories', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $query = $category->products()
            ->where('is_active', true);

        // Lọc theo khoảng giá
        $minPrice = request('min_price');
        $maxPrice = request('max_price');
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        // Lọc theo trạng thái khuyến mãi
        if (request('on_sale') === '1') {
            $query->where('is_flash_sale', true);
        }

        // Sắp xếp
        $sort = request('sort');
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
                $query->latest('id');
                break;
        }

        $products = $query->paginate(20)->withQueryString();

        return view('category', compact('category', 'products'));
    }
}
