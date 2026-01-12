<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Đã cập nhật trạng thái thanh toán!');
    }

    /**
     * Trang thống kê bán hàng
     */
    public function statistics(Request $request)
    {
        // Thống kê tổng quan
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalProductsSold = OrderItem::whereHas('order', function ($q) {
            $q->whereIn('status', ['completed', 'shipping', 'processing']);
        })->sum('quantity');
        $pendingOrders = Order::where('status', 'pending')->count();

        // Thống kê theo trạng thái đơn hàng
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Thống kê theo phương thức thanh toán
        $ordersByPaymentMethod = Order::select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        // Doanh thu 7 ngày gần nhất
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenue = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $last7Days->push([
                'date' => now()->subDays($i)->format('d/m'),
                'revenue' => $revenue,
            ]);
        }

        // Top sản phẩm bán chạy (đồng bộ với logic FeaturedProductService)
        // Group chỉ theo product_id để đảm bảo tính chính xác, lấy tên từ Product model
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['completed', 'shipping', 'processing']);
            })
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return (object) [
                    'product_id' => $item->product_id,
                    'product_name' => $product ? $product->name : 'Sản phẩm đã bị xóa',
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue,
                ];
            });

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // Thống kê theo tháng (12 tháng gần nhất)
        $monthlyStats = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
            $orders = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $monthlyStats->push([
                'month' => $month->format('m/Y'),
                'revenue' => $revenue,
                'orders' => $orders,
            ]);
        }

        return view('admin.orders.statistics', compact(
            'totalOrders',
            'totalRevenue',
            'totalProductsSold',
            'pendingOrders',
            'ordersByStatus',
            'ordersByPaymentMethod',
            'last7Days',
            'topProducts',
            'recentOrders',
            'monthlyStats'
        ));
    }
}

