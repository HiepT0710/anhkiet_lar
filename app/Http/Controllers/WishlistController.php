<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = Wishlist::with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('wishlist', compact('items'));
    }

    public function toggle(Request $request, Product $product)
    {
        $user = $request->user();

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích');
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích');
    }
}


