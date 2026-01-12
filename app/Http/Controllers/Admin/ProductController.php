<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Tìm kiếm theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'featured' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_names' => 'nullable|array',
            'color_names.*' => 'nullable|string|max:100',
            'color_hexes' => 'nullable|array',
            'color_hexes.*' => 'nullable|string|max:20',
            'color_images' => 'nullable|array',
            'color_images.*' => 'nullable|string|max:255',
            'size_labels' => 'nullable|array',
            'size_labels.*' => 'nullable|string|max:50',
            'size_descriptions' => 'nullable|array',
            'size_descriptions.*' => 'nullable|string|max:255',
        ]);

        $data = $validated;
        unset($data['color_names'], $data['color_hexes'], $data['color_images']);
        $data['featured'] = $request->boolean('featured');
        $data['is_flash_sale'] = $request->boolean('is_flash_sale');
        $data['is_active'] = $request->boolean('is_active');
        $data['sale_starts_at'] = $this->parseSaleDate($request->input('sale_starts_at'));
        $data['sale_ends_at'] = $this->parseSaleDate($request->input('sale_ends_at'));
        $data['color_options'] = $this->buildColorOptions(
            $request->input('color_names', []),
            $request->input('color_hexes', []),
            $request->input('color_images', [])
        );
        $data['size_options'] = $this->buildSizeOptions(
            $request->input('size_labels', []),
            $request->input('size_descriptions', [])
        );
        
        // Xử lý upload ảnh
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'featured' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'color_names' => 'nullable|array',
            'color_names.*' => 'nullable|string|max:100',
            'color_hexes' => 'nullable|array',
            'color_hexes.*' => 'nullable|string|max:20',
            'color_images' => 'nullable|array',
            'color_images.*' => 'nullable|string|max:255',
            'size_labels' => 'nullable|array',
            'size_labels.*' => 'nullable|string|max:50',
            'size_descriptions' => 'nullable|array',
            'size_descriptions.*' => 'nullable|string|max:255',
        ]);

        $data = $validated;
        unset($data['color_names'], $data['color_hexes'], $data['color_images']);
        $data['featured'] = $request->boolean('featured');
        $data['is_flash_sale'] = $request->boolean('is_flash_sale');
        $data['is_active'] = $request->boolean('is_active');
        $data['sale_starts_at'] = $this->parseSaleDate($request->input('sale_starts_at'));
        $data['sale_ends_at'] = $this->parseSaleDate($request->input('sale_ends_at'));
        $data['color_options'] = $this->buildColorOptions(
            $request->input('color_names', []),
            $request->input('color_hexes', []),
            $request->input('color_images', [])
        );
        $data['size_options'] = $this->buildSizeOptions(
            $request->input('size_labels', []),
            $request->input('size_descriptions', [])
        );
        
        // Xử lý upload ảnh mới
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật!');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return back()->with('success', 'Đã cập nhật trạng thái sản phẩm.');
    }

    private function buildColorOptions(array $names = [], array $hexes = [], array $images = []): ?array
    {
        $colorOptions = [];
        $max = max(count($names), count($hexes), count($images));

        for ($i = 0; $i < $max; $i++) {
            $name = trim($names[$i] ?? '');
            $hex = trim($hexes[$i] ?? '');
            $image = trim($images[$i] ?? '');

            if ($name === '' && $hex === '' && $image === '') {
                continue;
            }

            $colorOptions[] = [
                'name' => $name,
                'hex' => $hex ?: null,
                'image' => $image ?: null,
            ];
        }

        return count($colorOptions) ? $colorOptions : null;
    }

    private function buildSizeOptions(array $labels = [], array $descriptions = []): ?array
    {
        $sizeOptions = [];
        $max = max(count($labels), count($descriptions));

        for ($i = 0; $i < $max; $i++) {
            $label = trim($labels[$i] ?? '');
            $description = trim($descriptions[$i] ?? '');

            if ($label === '' && $description === '') {
                continue;
            }

            $sizeOptions[] = [
                'label' => $label,
                'description' => $description ?: null,
            ];
        }

        return count($sizeOptions) ? $sizeOptions : null;
    }

    private function parseSaleDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function inventory(Request $request)
    {
        $query = Product::with('category');
        
        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Lọc theo trạng thái tồn kho
        if ($request->has('stock_filter')) {
            switch ($request->stock_filter) {
                case 'out_of_stock':
                    $query->where('stock', '<=', 0);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                    break;
                case 'in_stock':
                    $query->where('stock', '>', 10);
                    break;
            }
        }
        
        $products = $query->orderBy('stock', 'asc')->orderBy('name', 'asc')->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        
        // Thống kê tổng quan
        $totalProducts = Product::count();
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $inStock = Product::where('stock', '>', 10)->count();
        $totalStockValue = Product::sum(\DB::raw('stock * price'));
        
        return view('admin.products.inventory', compact(
            'products', 
            'categories', 
            'totalProducts', 
            'outOfStock', 
            'lowStock', 
            'inStock',
            'totalStockValue'
        ));
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $validated['stock']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng tồn kho thành công!',
            'stock' => $product->stock
        ]);
    }
}
