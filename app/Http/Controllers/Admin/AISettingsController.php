<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class AISettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'enabled' => config('services.openai.enabled', false),
            'api_key' => config('services.openai.api_key') ? '***' . substr(config('services.openai.api_key'), -4) : '',
            'model' => config('services.openai.model', 'gpt-3.5-turbo'),
        ];

        $storeInfo = Cache::get('ai_store_info', $this->getDefaultStoreInfo());

        return view('admin.ai-settings.index', compact('settings', 'storeInfo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_info' => 'required|string|max:5000',
        ]);

        // Lưu store info vào cache (có thể thay bằng database nếu cần)
        Cache::put('ai_store_info', $validated['store_info'], now()->addYear());

        return redirect()->route('admin.ai-settings.index')
            ->with('success', 'Đã cập nhật thông tin cho AI!');
    }

    public function testConnection(Request $request)
    {
        $aiService = app(\App\Services\AIChatService::class);
        $result = $aiService->testConnection();
        
        return response()->json($result);
    }

    public function testAI(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $aiService = app(\App\Services\AIChatService::class);

        if (!$aiService->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'AI chưa được bật. Vui lòng kiểm tra:\n1. OPENAI_ENABLED=true trong file .env\n2. OPENAI_API_KEY đã được cấu hình',
            ]);
        }

        try {
            $response = $aiService->chat($validated['message']);

            if ($response) {
                return response()->json([
                    'success' => true,
                    'response' => $response,
                ]);
            }

            // Lấy log lỗi gần nhất
            $logFile = storage_path('logs/laravel.log');
            $errorMessage = 'Không thể kết nối với AI.';
            
            if (file_exists($logFile)) {
                $logs = file_get_contents($logFile);
                $lines = explode("\n", $logs);
                $recentLogs = array_slice($lines, -20);
                $errorLog = '';
                foreach ($recentLogs as $line) {
                    if (stripos($line, 'AI Chat') !== false || stripos($line, 'openai') !== false) {
                        $errorLog = $line;
                        break;
                    }
                }
                if ($errorLog) {
                    $errorMessage .= ' Chi tiết: ' . strip_tags($errorLog);
                }
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage . '\n\nVui lòng kiểm tra:\n1. API Key có đúng không\n2. API Key còn credit không\n3. Kết nối internet',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ]);
        }
    }

    protected function getDefaultStoreInfo(): string
    {
        return <<<PROMPT
Bạn là trợ lý ảo của AnhKiet Store - cửa hàng bán điện thoại, phụ kiện công nghệ uy tín tại Việt Nam.

THÔNG TIN CỬA HÀNG:
- Tên: AnhKiet Store
- Hotline: 0973910053 (8:00 - 21:00 hàng ngày)
- Email: nguyenvoanhkiet2@gmail.comcom
- Website: anhkiet.com
- Địa chỉ: 60e Tam BÌnh Thủ Đức 

SẢN PHẨM CHÍNH:
- iPhone các dòng (iPhone 15, 14, 13...)
- Samsung Galaxy
- Phụ kiện: ốp lưng, sạc, cáp, tai nghe...
- Đồng hồ thông minh

CHÍNH SÁCH:
- Bảo hành: 12 tháng chính hãng
- Đổi trả: 7 ngày nếu lỗi từ nhà sản xuất
- Giao hàng: Miễn phí với đơn từ 500.000đ
- Trả góp: 0% lãi suất qua thẻ tín dụng

HƯỚNG DẪN TRẢ LỜI:
1. Trả lời ngắn gọn, thân thiện, dùng emoji phù hợp
2. Nếu không biết giá chính xác, hướng dẫn khách xem trên website hoặc gọi hotline
3. Nếu câu hỏi phức tạp, đề nghị khách để lại số điện thoại để nhân viên liên hệ
4. Luôn sẵn sàng hỗ trợ và tạo thiện cảm với khách hàng
5. Trả lời bằng tiếng Việt
6. KHÔNG bịa đặt thông tin về giá cả, khuyến mãi cụ thể nếu không chắc chắn
PROMPT;
    }
}

