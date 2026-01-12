<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CouponService;
use App\Services\SePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected CouponService $couponService;
    protected SePayService $sepayService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function showCheckout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống, chưa thể thanh toán.');
        }

        // Bổ sung URL ảnh sản phẩm cho từng item (giống trang giỏ hàng)
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
                        $imageUrl = Str::startsWith($firstImage, ['http://', 'https://'])
                            ? $firstImage
                            : asset('storage/' . ltrim($firstImage, '/'));
                        $item['image_url'] = $imageUrl;
                    }
                }
            }

            return $item;
        })->all();

        $subtotal = collect($cart)->reduce(function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        // Lấy coupon từ session nếu có
        $couponCode = $request->session()->get('applied_coupon_code');
        $couponData = null;
        $discount = 0;
        $total = $subtotal;

        if ($couponCode) {
            $result = $this->couponService->validateAndCalculate($couponCode, $subtotal);
            if ($result['valid']) {
                $couponData = $result['coupon'];
                $discount = $result['discount'];
                $total = $result['final_amount'];
            } else {
                // Coupon không hợp lệ, xóa khỏi session
                $request->session()->forget('applied_coupon_code');
            }
        }

        return view('checkout', compact('cart', 'subtotal', 'discount', 'total', 'couponData', 'couponCode'));
    }

    public function applyCoupon(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng đang trống.',
            ]);
        }

        $validated = $request->validate([
            'coupon_code' => 'nullable|string|max:64',
        ]);

        // Nếu coupon_code rỗng thì xóa coupon
        if (empty(trim($validated['coupon_code'] ?? ''))) {
            $request->session()->forget('applied_coupon_code');
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa mã giảm giá.',
            ]);
        }

        $subtotal = collect($cart)->reduce(function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        $result = $this->couponService->validateAndCalculate($validated['coupon_code'], $subtotal);

        if ($result['valid']) {
            $request->session()->put('applied_coupon_code', strtoupper(trim($validated['coupon_code'])));
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'discount' => $result['discount'],
                'final_amount' => $result['final_amount'],
                'coupon' => [
                    'code' => $result['coupon']->code,
                    'type' => $result['coupon']->type,
                    'value' => $result['coupon']->value,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ]);
    }

    public function createPayment(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống, chưa thể thanh toán.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'required|string|max:255',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,bank_qr,momo_qr,sepay_qr',
        ]);

        $subtotal = collect($cart)->reduce(function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        // Xử lý coupon nếu có
        $couponCode = $request->session()->get('applied_coupon_code');
        $couponId = null;
        $discountAmount = 0;
        $total = $subtotal;

        if ($couponCode) {
            $result = $this->couponService->validateAndCalculate($couponCode, $subtotal);
            if ($result['valid']) {
                $couponId = $result['coupon']->id;
                $discountAmount = $result['discount'];
                $total = $result['final_amount'];
                // Tăng số lượt sử dụng
                $this->couponService->incrementUsage($result['coupon']);
            }
        }

        $orderCode = 'AK' . now()->format('ymdHis') . Str::padLeft((string) random_int(0, 999), 3, '0');

        $order = Order::create([
            'user_id' => $request->user()->id ?? null,
            'code' => $orderCode,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_address' => $data['customer_address'],
            'note' => $data['note'] ?? null,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discountAmount,
            'total_amount' => $total,
            'coupon_id' => $couponId,
            'payment_method' => $data['payment_method'],
            'payment_status' => ($data['payment_method'] === 'cod') ? 'pending' : 'unpaid',
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'selected_color' => $item['selected_color'] ?? null,
                'selected_size' => $item['selected_size'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity'],
            ]);
        }

        // Xóa coupon khỏi session sau khi đặt hàng
        $request->session()->forget('applied_coupon_code');

        // Nếu thanh toán khi nhận hàng (COD) thì không gọi cổng thanh toán
        if ($data['payment_method'] === 'cod') {
            // Đơn hàng ở trạng thái chờ xử lý, payment_status = pending
            $request->session()->forget('cart');

            return redirect()->route('cart.index')
                ->with('success', 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
        }

        // Các phương thức online (QR ngân hàng / MoMo / SePay) => hiển thị trang quét QR nội bộ
        $request->session()->forget('cart');

        return redirect()->route('payment.qr', ['code' => $order->code]);
    }

    public function showQr(Request $request, string $code)
    {
        $order = Order::where('code', $code)->first();
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng để thanh toán.');
        }

        $qrImage = null;
        $methodLabel = '';
        $helperText = '';
        $payment_url = null;
        $sepay_debug = null;

        if ($order->payment_method === 'bank_qr') {
            $qrImage = asset('images/qr-bank.png'); // Đổi thành đường dẫn QR ngân hàng của bạn
            $methodLabel = 'Quét mã QR ngân hàng';
            $helperText = 'Mở app ngân hàng, chọn Quét mã QR và thanh toán đúng số tiền và nội dung bên dưới.';
        } elseif ($order->payment_method === 'momo_qr') {
            $qrImage = asset('images/qr-momo.png'); // Đổi thành đường dẫn QR MoMo của bạn
            $methodLabel = 'Quét mã QR MoMo';
            $helperText = 'Mở app MoMo, chọn Quét mã / Thanh toán và quét mã dưới đây.';
        } elseif ($order->payment_method === 'sepay_qr') {
            // Generate SePay QR using service
            $this->sepayService = app(SePayService::class);
            $result = $this->sepayService->createQr([
                'order_code' => $order->code,
                'amount' => $order->total_amount,
                'return_url' => route('payment.sepay.return'),
                'notify_url' => route('payment.sepay.webhook'),
            ]);

            $qrImage = $result['qr_image'] ?? null;
            $payment_url = $result['payment_url'] ?? null;
            \Log::info('SePay createQr result', ['order' => $order->code, 'result' => $result]);
            $sepay_debug = $result;

            // Fallback to Google Chart QR if SePay did not return an image
            if (empty($qrImage)) {
                $fallbackPayload = json_encode([
                    'order_code' => $order->code,
                    'amount' => $order->total_amount,
                ]);
                $qrImage = 'https://chart.googleapis.com/chart?cht=qr&chs=400x400&chl=' . urlencode($fallbackPayload);
            }

            $methodLabel = 'Quét mã QR SePay';
            $helperText = 'Mở app hỗ trợ quét QR, quét mã để thanh toán đơn hàng.';
        } else {
            return redirect()->route('cart.index')->with('error', 'Phương thức thanh toán không hợp lệ.');
        }

        return view('payment_qr', compact('order', 'qrImage', 'methodLabel', 'helperText', 'payment_url', 'sepay_debug'));
    }

    /**
     * SePay return (customer redirect) - basic handler, adjust according to SePay docs.
     */
    public function handleSePayReturn(Request $request)
    {
        $orderCode = $request->input('order_code') ?? $request->input('orderId') ?? null;
        $status = $request->input('status') ?? null;

        if (!$orderCode) {
            return redirect()->route('cart.index')->with('error', 'Thiếu mã đơn hàng từ SePay.');
        }

        $order = Order::where('code', $orderCode)->first();
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Đơn hàng không tồn tại.');
        }

        // This is a simplistic handler. You should validate signature/payment status against SePay.
        if ($status === 'success') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->save();
            return redirect()->route('account.orders')->with('success', 'Thanh toán thành công qua SePay.');
        }

        return redirect()->route('account.orders')->with('error', 'Thanh toán không thành công hoặc bị huỷ.');
    }

    /**
     * SePay webhook (notify) - processes remote notifications from SePay.
     * For now we accept a basic structure; please adapt according to SePay webhook docs.
     */
    public function handleSePayWebhook(Request $request)
    {
        $orderCode = $request->input('order_code') ?? $request->input('orderId') ?? null;
        $status = $request->input('status') ?? null;

        if (!$orderCode) {
            return response('missing_order', 400);
        }

        $order = Order::where('code', $orderCode)->first();
        if (!$order) {
            return response('order_not_found', 404);
        }

        if ($status === 'success') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->save();
            return response('ok', 200);
        }

        return response('ignored', 200);
    }
}


