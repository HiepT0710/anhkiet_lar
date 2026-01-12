<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalBanners' => Banner::count(),
            'totalUsers' => User::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'activeCategories' => Category::where('is_active', true)->count(),
            // Thống kê đơn hàng
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'totalProductsSold' => OrderItem::whereHas('order', function ($q) {
                $q->whereIn('status', ['completed', 'shipping', 'processing']);
            })->sum('quantity'),
        ];

        $recentProducts = Product::latest()->take(5)->get();
        $recentBanners = Banner::latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'recentBanners', 'recentOrders'));
    }
}
