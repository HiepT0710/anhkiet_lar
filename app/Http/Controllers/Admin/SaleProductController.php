<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter theo trạng thái sale
        $status = $request->get('status', 'all');
        $now = now();

        switch ($status) {
            case 'active':
                // Đang sale
                $query->where('is_flash_sale', true)
                      ->whereNotNull('sale_price')
                      ->whereColumn('sale_price', '<', 'price')
                      ->where(function ($q) use ($now) {
                          $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
                      })
                      ->where(function ($q) use ($now) {
                          $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
                      });
                break;
            
            case 'upcoming':
                // Sắp sale
                $query->where('is_flash_sale', true)
                      ->whereNotNull('sale_starts_at')
                      ->where('sale_starts_at', '>', $now);
                break;
            
            case 'ended':
                // Đã kết thúc
                $query->where('is_flash_sale', true)
                      ->whereNotNull('sale_ends_at')
                      ->where('sale_ends_at', '<', $now);
                break;
            
            case 'no_sale':
                // Không có sale
                $query->where(function ($q) {
                    $q->where('is_flash_sale', false)
                      ->orWhereNull('sale_price')
                      ->orWhereColumn('sale_price', '>=', 'price');
                });
                break;
            
            case 'all':
            default:
                // Tất cả
                break;
        }

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'id_desc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'sale_price_asc':
                $query->orderBy('sale_price', 'asc');
                break;
            case 'sale_price_desc':
                $query->orderBy('sale_price', 'desc');
                break;
            case 'ends_at_asc':
                $query->orderBy('sale_ends_at', 'asc');
                break;
            case 'ends_at_desc':
                $query->orderBy('sale_ends_at', 'desc');
                break;
            case 'id_desc':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(20)->appends(request()->query());
        $categories = Category::where('is_active', true)->orderBy('order')->get();

        // Thống kê
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_flash_sale', true)
                ->whereNotNull('sale_price')
                ->whereColumn('sale_price', '<', 'price')
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_starts_at')->orWhere('sale_starts_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('sale_ends_at')->orWhere('sale_ends_at', '>=', $now);
                })
                ->count(),
            'upcoming' => Product::where('is_flash_sale', true)
                ->whereNotNull('sale_starts_at')
                ->where('sale_starts_at', '>', $now)
                ->count(),
            'ended' => Product::where('is_flash_sale', true)
                ->whereNotNull('sale_ends_at')
                ->where('sale_ends_at', '<', $now)
                ->count(),
            'no_sale' => Product::where(function ($q) {
                $q->where('is_flash_sale', false)
                  ->orWhereNull('sale_price')
                  ->orWhereColumn('sale_price', '>=', 'price');
            })->count(),
        ];

        return view('admin.sale-products.index', compact('products', 'categories', 'status', 'stats'));
    }

    public function bulkSetSale(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'sale_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'is_flash_sale' => 'nullable|boolean',
        ]);

        $productIds = $request->product_ids;
        $updateData = [];

        if ($request->has('is_flash_sale')) {
            $updateData['is_flash_sale'] = $request->boolean('is_flash_sale');
        }

        if ($request->filled('sale_starts_at')) {
            $updateData['sale_starts_at'] = $request->sale_starts_at;
        }

        if ($request->filled('sale_ends_at')) {
            $updateData['sale_ends_at'] = $request->sale_ends_at;
        }

        // Tính giá sale từ % giảm giá hoặc giá sale trực tiếp
        if ($request->filled('discount_percent')) {
            $products = Product::whereIn('id', $productIds)->get();
            foreach ($products as $product) {
                $salePrice = round($product->price * (1 - $request->discount_percent / 100));
                Product::where('id', $product->id)->update(array_merge($updateData, [
                    'sale_price' => $salePrice,
                    'is_flash_sale' => true,
                ]));
            }
        } elseif ($request->filled('sale_price')) {
            $updateData['sale_price'] = $request->sale_price;
            Product::whereIn('id', $productIds)->update($updateData);
        } else {
            Product::whereIn('id', $productIds)->update($updateData);
        }

        return back()->with('success', 'Đã cập nhật ' . count($productIds) . ' sản phẩm thành công!');
    }

    public function removeSale(Request $request, Product $product)
    {
        $product->update([
            'is_flash_sale' => false,
            'sale_price' => null,
            'sale_starts_at' => null,
            'sale_ends_at' => null,
        ]);

        return back()->with('success', 'Đã gỡ sale cho sản phẩm "' . $product->name . '"');
    }
}
