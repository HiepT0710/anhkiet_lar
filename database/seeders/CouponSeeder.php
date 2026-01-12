<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa các mã cũ nếu có (tránh trùng lặp)
        Coupon::whereIn('code', ['SALE10', 'GIAM50K', 'FREESHIP', 'SALE20', 'NEWUSER'])->delete();

        $coupons = [
            [
                'code' => 'SALE10',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 50000,
                'min_order' => null,
                'start_at' => null,
                'end_at' => Carbon::now()->addMonths(3), // Hết hạn sau 3 tháng
                'usage_limit' => 1000,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'GIAM50K',
                'type' => 'fixed',
                'value' => 50000,
                'max_discount' => null,
                'min_order' => 500000, // Đơn tối thiểu 500.000đ
                'start_at' => null,
                'end_at' => Carbon::now()->addMonths(2),
                'usage_limit' => 500,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 30000,
                'max_discount' => null,
                'min_order' => 200000, // Đơn tối thiểu 200.000đ
                'start_at' => null,
                'end_at' => null, // Không hết hạn
                'usage_limit' => null, // Không giới hạn lượt dùng
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SALE20',
                'type' => 'percent',
                'value' => 20,
                'max_discount' => null, // Không giới hạn giảm tối đa
                'min_order' => 1000000, // Đơn tối thiểu 1.000.000đ
                'start_at' => null,
                'end_at' => Carbon::now()->addMonths(1),
                'usage_limit' => 200,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'NEWUSER',
                'type' => 'fixed',
                'value' => 100000,
                'max_discount' => null,
                'min_order' => 1000000, // Đơn tối thiểu 1.000.000đ
                'start_at' => null,
                'end_at' => Carbon::now()->addMonths(6),
                'usage_limit' => 1000,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }

        $this->command->info('✅ Đã tạo 5 mã giảm giá mẫu:');
        $this->command->info('  1. SALE10 - Giảm 10%, tối đa 50.000đ');
        $this->command->info('  2. GIAM50K - Giảm 50.000đ, đơn tối thiểu 500.000đ');
        $this->command->info('  3. FREESHIP - Miễn phí ship 30.000đ, đơn tối thiểu 200.000đ');
        $this->command->info('  4. SALE20 - Giảm 20%, đơn tối thiểu 1.000.000đ');
        $this->command->info('  5. NEWUSER - Giảm 100.000đ cho khách mới, đơn tối thiểu 1.000.000đ');
    }
}
