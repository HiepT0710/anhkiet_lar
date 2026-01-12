<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Log;

class CouponService
{
    /**
     * Validate và tính toán discount từ coupon code
     */
    public function validateAndCalculate(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã bị vô hiệu hóa.',
            ];
        }

        // Kiểm tra thời gian hiệu lực
        $now = now();
        if ($coupon->start_at && $now->lt($coupon->start_at)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá chưa có hiệu lực.',
            ];
        }

        if ($coupon->end_at && $now->gt($coupon->end_at)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết hạn.',
            ];
        }

        // Kiểm tra đơn hàng tối thiểu
        if ($coupon->min_order && $subtotal < $coupon->min_order) {
            return [
                'valid' => false,
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order) . 'đ để áp dụng mã này.',
            ];
        }

        // Kiểm tra số lượt sử dụng
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá đã hết lượt sử dụng.',
            ];
        }

        // Tính toán discount
        $discount = 0;
        if ($coupon->type === 'percent') {
            $discount = ($subtotal * $coupon->value) / 100;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = $coupon->max_discount;
            }
        } else {
            // Fixed amount
            $discount = min($coupon->value, $subtotal);
        }

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => round($discount, 2),
            'final_amount' => max(0, $subtotal - $discount),
            'message' => 'Áp dụng mã giảm giá thành công!',
        ];
    }

    /**
     * Tăng số lượt sử dụng của coupon
     */
    public function incrementUsage(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }
}

