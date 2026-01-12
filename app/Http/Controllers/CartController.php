<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        // Bổ sung ảnh đầy đủ URL cho từng item trong giỏ mỗi lần load trang
        $cart = collect($cart)->map(function ($item) {
            $productId = $item['id'] ?? null;
            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $rawImages = $product->images;
                    if (is_string($rawImages)) {
                        $decoded = json_decode($rawImages, true);
                        $rawImages = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                    }
                    $firstImage = is_array($rawImages) && count($rawImages) ? $rawImages[0] : null;

                    if ($firstImage) {
                        // Nếu là URL tuyệt đối thì dùng luôn, ngược lại convert sang asset storage
                        $imageUrl = Str::startsWith($firstImage, ['http://', 'https://'])
                            ? $firstImage
                            : asset('storage/' . ltrim($firstImage, '/'));
                        $item['image_url'] = $imageUrl;
                    }
                }
            }

            return $item;
        })->all();

        $total = collect($cart)->reduce(function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);
        return view('cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'selected_color' => 'nullable|string|max:100',
            'selected_size' => 'nullable|string|max:100',
            'purchase_type' => 'nullable|string|in:buy_now,add_to_cart',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Kiểm tra số lượng tồn kho
        if ($product->stock <= 0) {
            return back()->with('error', 'Sản phẩm đã hết hàng. Vui lòng chọn sản phẩm khác.');
        }

        // Lấy ảnh đầu tiên của sản phẩm, hỗ trợ cả dạng mảng và JSON string
        $rawImages = $product->images;
        if (is_string($rawImages)) {
            $decoded = json_decode($rawImages, true);
            $rawImages = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }
        $firstImage = is_array($rawImages) && count($rawImages) ? $rawImages[0] : null;
        $quantity = $validated['quantity'] ?? 1;
        $quantity = max(1, $quantity);
        $quantity = min($quantity, $product->stock);

        $selectedColor = $validated['selected_color'] ?? null;
        $selectedSize = $validated['selected_size'] ?? null;

        // Tính lại giá theo phiên bản (dung lượng) nếu có chọn
        [$finalPrice, $listPrice] = $this->resolvePriceForSize($product, $selectedSize);

        $rowId = $product->id . '|' . md5(($selectedColor ?? '') . '|' . ($selectedSize ?? ''));

        $cart = $request->session()->get('cart', []);
        if (isset($cart[$rowId])) {
            $newQuantity = $cart[$rowId]['quantity'] + $quantity;
            $newQuantity = min($newQuantity, $product->stock);
            $cart[$rowId]['quantity'] = $newQuantity;
        } else {
            $cart[$rowId] = [
                'row_id' => $rowId,
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$finalPrice,
                'original_price' => (float)$listPrice,
                'is_sale_active' => $product->is_sale_active,
                'quantity' => $quantity,
                'image' => $firstImage,
                'slug' => $product->slug,
                'selected_color' => $selectedColor,
                'selected_size' => $selectedSize,
            ];
        }

        $request->session()->put('cart', $cart);

        // Nếu bấm "MUA NGAY" thì chuyển thẳng đến trang thanh toán
        $purchaseType = $validated['purchase_type'] ?? 'add_to_cart';
        if ($purchaseType === 'buy_now') {
            return redirect()->route('checkout.show');
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'row_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);
        if (isset($cart[$validated['row_id']])) {
            $product = Product::find($cart[$validated['row_id']]['id']);
            if (!$product || $product->stock <= 0) {
                // Xóa sản phẩm khỏi giỏ nếu hết hàng
                unset($cart[$validated['row_id']]);
                $request->session()->put('cart', $cart);
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "' . ($product->name ?? '') . '" đã hết hàng và đã được xóa khỏi giỏ hàng.');
            }
            $quantity = $validated['quantity'];
            $quantity = min($quantity, $product->stock);
            $cart[$validated['row_id']]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'row_id' => 'required|string',
        ]);
        $cart = $request->session()->get('cart', []);
        unset($cart[$validated['row_id']]);
        $request->session()->put('cart', $cart);
        return redirect()->route('cart.index');
    }

    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống, chưa thể thanh toán.');
        }

        $request->session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Thanh toán thành công! Cảm ơn bạn đã mua sắm tại AnhKiet Store.');
    }

    /**
     * Tính giá theo phiên bản (dung lượng) dựa trên selected_size
     */
    private function resolvePriceForSize(Product $product, ?string $selectedSize): array
    {
        $baseFinal = (float) $product->final_price;
        $baseList = (float) $product->price;

        if (!$selectedSize) {
            return [$baseFinal, $baseList];
        }

        $options = [];

        if (is_array($product->size_options)) {
            $options = $product->size_options;
        } else {
            $predefined = config('product_versions');
            $options = $predefined[$product->slug] ?? [];
        }

        foreach ($options as $option) {
            if (empty($option['label']) || $option['label'] !== $selectedSize) {
                continue;
            }

            $description = $option['description'] ?? null;
            $delta = 0;

            if ($description && preg_match('/([+\-])\s*([\d\.]+)\s*đ/u', $description, $m)) {
                $sign = $m[1] === '-' ? -1 : 1;
                $amount = (int) str_replace('.', '', $m[2]);
                $delta = $sign * $amount;
            }

            return [$baseFinal + $delta, $baseList + $delta];
        }

        return [$baseFinal, $baseList];
    }
}


