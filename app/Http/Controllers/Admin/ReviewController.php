<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user'])->latest();

        // Lọc theo trạng thái duyệt
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('comment', 'like', '%' . $search . '%')
                  ->orWhereHas('product', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        // Thống kê
        $totalReviews = Review::count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $pendingReviews = Review::where('is_approved', false)->count();

        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'approvedReviews',
            'pendingReviews'
        ));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Đã duyệt đánh giá thành công!');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        return back()->with('success', 'Đã từ chối đánh giá!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công!');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $validated['review_ids'])->update(['is_approved' => true]);
        
        return back()->with('success', 'Đã duyệt ' . count($validated['review_ids']) . ' đánh giá thành công!');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $validated['review_ids'])->delete();
        
        return back()->with('success', 'Đã xóa ' . count($validated['review_ids']) . ' đánh giá thành công!');
    }
}

