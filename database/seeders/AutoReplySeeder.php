<?php

namespace Database\Seeders;

use App\Models\AutoReply;
use Illuminate\Database\Seeder;

class AutoReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autoReplies = [
            [
                'keywords' => 'xin chào, hello, hi, chào',
                'response' => 'Xin chào! 👋 Cảm ơn bạn đã liên hệ với AnhKiet Store. Tôi có thể giúp gì cho bạn?',
                'priority' => 100,
            ],
            [
                'keywords' => 'giá, bao nhiêu tiền, giá bao nhiêu, giá cả',
                'response' => 'Để biết giá sản phẩm chính xác, bạn vui lòng truy cập trang sản phẩm trên website hoặc liên hệ hotline 1900.9999 để được tư vấn chi tiết nhé! 💰',
                'priority' => 80,
            ],
            [
                'keywords' => 'giao hàng, ship, vận chuyển, delivery',
                'response' => '🚚 AnhKiet Store hỗ trợ giao hàng toàn quốc:\n- Nội thành: 2-4 giờ\n- Ngoại thành: 1-3 ngày\n- Miễn phí giao hàng với đơn từ 500.000đ',
                'priority' => 70,
            ],
            [
                'keywords' => 'bảo hành, warranty, bảo trì',
                'response' => '🛡️ Chính sách bảo hành:\n- iPhone: 12 tháng chính hãng\n- Phụ kiện: 6-12 tháng\n- Đổi mới trong 30 ngày nếu lỗi từ nhà sản xuất',
                'priority' => 70,
            ],
            [
                'keywords' => 'trả góp, góp, installment, tra gop',
                'response' => '💳 AnhKiet Store hỗ trợ trả góp 0% lãi suất qua:\n- Thẻ tín dụng: Visa, Mastercard, JCB\n- Công ty tài chính: Home Credit, FE Credit\nLiên hệ 1900.9999 để được tư vấn!',
                'priority' => 70,
            ],
            [
                'keywords' => 'đổi trả, hoàn tiền, return, đổi hàng',
                'response' => '🔄 Chính sách đổi trả:\n- Đổi trả trong 7 ngày nếu sản phẩm lỗi\n- Sản phẩm phải còn nguyên tem, hộp\n- Hoàn tiền trong 3-5 ngày làm việc',
                'priority' => 60,
            ],
            [
                'keywords' => 'địa chỉ, cửa hàng, showroom, ở đâu',
                'response' => '📍 Địa chỉ showroom AnhKiet Store:\n- 123 Nguyễn Huệ, Q.1, TP.HCM\n- 456 Lê Lợi, Q.1, TP.HCM\nGiờ mở cửa: 8:00 - 21:00 hàng ngày',
                'priority' => 60,
            ],
            [
                'keywords' => 'liên hệ, hotline, số điện thoại, phone',
                'response' => '📞 Liên hệ AnhKiet Store:\n- Hotline: 1900.9999 (8:00 - 21:00)\n- Email: support@anhkiet.com\n- Zalo: 0987.654.321',
                'priority' => 60,
            ],
            [
                'keywords' => 'khuyến mãi, giảm giá, sale, voucher, mã giảm',
                'response' => '🎉 Khuyến mãi hiện tại:\n- Giảm đến 20% cho iPhone 15 Series\n- Tặng phụ kiện trị giá 500K\n- Nhập mã ANHKIET10 giảm thêm 10%\nXem thêm tại: anhkiet.com/khuyen-mai',
                'priority' => 50,
            ],
            [
                'keywords' => 'còn hàng, hết hàng, stock, inventory',
                'response' => 'Để kiểm tra tình trạng còn hàng của sản phẩm, bạn vui lòng cho mình biết tên sản phẩm cụ thể hoặc liên hệ hotline 1900.9999 để được hỗ trợ nhanh nhất nhé! 📱',
                'priority' => 50,
            ],
            [
                'keywords' => 'cảm ơn, thank, thanks',
                'response' => 'Cảm ơn bạn đã liên hệ với AnhKiet Store! 😊 Nếu có bất kỳ câu hỏi nào khác, đừng ngại nhắn tin cho chúng tôi nhé!',
                'priority' => 40,
            ],
        ];

        foreach ($autoReplies as $reply) {
            AutoReply::updateOrCreate(
                ['keywords' => $reply['keywords']],
                $reply
            );
        }
    }
}

