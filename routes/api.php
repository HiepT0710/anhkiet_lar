<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Sản phẩm - danh sách
 */
Route::get('/products', function (Request $request) {
    $query = Product::where('is_active', true);

    if ($request->filled('category')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('slug', $request->category);
        });
    }

    $products = $query->latest('id')->take(100)->get()->map(function ($p) {
        $images = is_string($p->images) ? json_decode($p->images, true) : $p->images;
        $first = is_array($images) && count($images) ? $images[0] : null;
        $imageUrl = $first
            ? (Str::startsWith($first, ['http', 'https']) ? $first : asset('storage/' . ltrim($first, '/')))
            : null;

        return [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => (float) $p->price,
            'final_price' => (float) $p->final_price,
            'image' => $imageUrl,
            'is_sale_active' => (bool) $p->is_sale_active,
            'stock' => (int) $p->stock,
        ];
    });

    return response()->json(['data' => $products]);
});

/**
 * Sản phẩm - chi tiết
 */
Route::get('/products/{slug}', function (string $slug) {
    $p = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

    $images = is_string($p->images) ? json_decode($p->images, true) : $p->images;
    $normalizedImages = collect($images ?? [])->filter()->map(function ($path) {
        return Str::startsWith($path, ['http', 'https'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    })->values();

    $firstImage = $normalizedImages->first();

    return response()->json([
        'id' => $p->id,
        'name' => $p->name,
        'slug' => $p->slug,
        'description' => $p->description,
        'price' => (float) $p->price,
        'final_price' => (float) $p->final_price,
        'is_sale_active' => (bool) $p->is_sale_active,
        'stock' => (int) $p->stock,
        'images' => $normalizedImages,
        'image' => $firstImage,
    ]);
});

/**
 * Danh mục
 */
Route::get('/categories', function () {
    $cats = Category::where('is_active', true)
        ->orderBy('order')
        ->get(['id', 'name', 'slug']);

    return response()->json(['data' => $cats]);
});

/**
 * Trang chủ (featured + latest)
 */
Route::get('/home', function () {
    $map = function ($p) {
        $images = is_string($p->images) ? json_decode($p->images, true) : $p->images;
        $first = is_array($images) && count($images) ? $images[0] : null;
        $imageUrl = $first
            ? (Str::startsWith($first, ['http', 'https']) ? $first : asset('storage/' . ltrim($first, '/')))
            : null;

        return [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => (float) $p->price,
            'final_price' => (float) $p->final_price,
            'image' => $imageUrl,
        ];
    };

    $featured = Product::where('is_active', true)
        ->where('featured', true)
        ->latest('id')->take(8)->get()->map($map);

    $latest = Product::where('is_active', true)
        ->latest('id')->take(12)->get()->map($map);

    return response()->json([
        'featured' => $featured,
        'latest' => $latest,
    ]);
});

/**
 * Auth - đăng ký / đăng nhập / đăng xuất / user (token Sanctum)
 */
Route::post('/auth/register', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);
    $token = $user->createToken('api')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token], 201);
});

Route::post('/auth/login', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);
    if (!Auth::attempt($data)) {
        return response()->json(['message' => 'Thông tin đăng nhập không đúng'], 401);
    }
    $user = $request->user();
    $token = $user->createToken('api')->plainTextToken;
    return response()->json(['user' => $user, 'token' => $token]);
});

Route::middleware('auth:sanctum')->post('/auth/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Đã đăng xuất']);
});

Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

/**
 * Cart preview (client gửi cart -> server tính giá)
 */
Route::post('/cart/preview', function (Request $request) {
    $data = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.slug' => 'required|string',
        'items.*.qty' => 'required|integer|min:1',
    ]);
    $items = [];
    $total = 0;
    foreach ($data['items'] as $row) {
        $p = Product::where('slug', $row['slug'])->where('is_active', true)->first();
        if (!$p) {
            return response()->json(['message' => 'Sản phẩm không tồn tại: ' . $row['slug']], 422);
        }
        $price = (float) $p->final_price;
        $line = $price * (int) $row['qty'];
        $total += $line;
        $items[] = [
            'slug' => $p->slug,
            'name' => $p->name,
            'qty' => (int) $row['qty'],
            'price' => $price,
            'line_total' => $line,
        ];
    }
    return response()->json(['items' => $items, 'total' => $total]);
});

/**
 * Checkout: lưu Order + OrderItems (COD)
 */
Route::post('/checkout', function (Request $request) {
    $data = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'customer_email' => 'nullable|email|max:255',
        'customer_address' => 'required|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.slug' => 'required|string',
        'items.*.qty' => 'required|integer|min:1',
    ]);

    $items = [];
    $total = 0;
    foreach ($data['items'] as $row) {
        $p = Product::where('slug', $row['slug'])->where('is_active', true)->first();
        if (!$p) {
            return response()->json(['message' => 'Sản phẩm không tồn tại: ' . $row['slug']], 422);
        }
        $price = (float) $p->final_price;
        $line = $price * (int) $row['qty'];
        $total += $line;
        $items[] = [
            'product' => $p,
            'qty' => (int) $row['qty'],
            'price' => $price,
            'line_total' => $line,
        ];
    }

    $order = Order::create([
        'user_id' => $request->user()->id ?? null,
        'code' => 'AK' . now()->format('ymdHis') . Str::padLeft((string) random_int(0, 999), 3, '0'),
        'customer_name' => $data['customer_name'],
        'customer_phone' => $data['customer_phone'],
        'customer_email' => $data['customer_email'] ?? null,
        'customer_address' => $data['customer_address'],
        'subtotal_amount' => $total,
        'discount_amount' => 0,
        'total_amount' => $total,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'status' => 'pending',
    ]);

    foreach ($items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product']->id,
            'product_name' => $item['product']->name,
            'quantity' => $item['qty'],
            'unit_price' => $item['price'],
            'total_price' => $item['line_total'],
        ]);
    }

    return response()->json([
        'message' => 'Đặt hàng thành công (COD)',
        'order_code' => $order->code,
        'total' => $total,
    ]);
});
