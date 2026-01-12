<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Kiểm tra nếu user đã đăng nhập
        $userId = auth()->id();
        
        // Validation rules - name chỉ required nếu chưa đăng nhập
        $rules = [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
        ];

        // Nếu chưa đăng nhập, yêu cầu nhập tên
        if (!$userId) {
            $rules['name'] = 'required|string|max:255';
        } else {
            $rules['name'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules);

        $product = Product::findOrFail($validated['product_id']);

        // Tạo đánh giá
        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'name' => $validated['name'] ?? auth()->user()->name ?? 'Khách hàng',
            'email' => $validated['email'] ?? auth()->user()->email ?? null,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => false, // Cần admin duyệt
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đang chờ được duyệt.');
    }
}

