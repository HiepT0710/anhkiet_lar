<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIChatService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? '';
        $this->model = config('services.openai.model') ?? 'gpt-3.5-turbo';
        $this->baseUrl = config('services.openai.base_url') ?? 'https://api.openai.com/v1';
    }

    /**
     * Kiểm tra xem AI có được cấu hình không
     */
    public function isEnabled(): bool
    {
        return !empty($this->apiKey) && config('services.openai.enabled', false);
    }

    /**
     * Test kết nối với OpenAI API
     */
    public function testConnection(): array
    {
        if (!$this->isEnabled()) {
            return [
                'success' => false,
                'message' => 'AI chưa được bật hoặc API Key chưa được cấu hình.',
            ];
        }

        try {
            // Test với một request đơn giản
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => 'Hello'],
                ],
                'max_tokens' => 10,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Kết nối thành công!',
                ];
            }

            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? $response->body();

            return [
                'success' => false,
                'message' => 'Lỗi từ OpenAI: ' . $errorMessage,
                'status' => $response->status(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Gửi tin nhắn đến AI và nhận phản hồi
     */
    public function chat(string $userMessage, array $conversationHistory = []): ?string
    {
        if (!$this->isEnabled()) {
            return null;
        }

        try {
            $systemPrompt = $this->getSystemPrompt();
            
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
            ];

            // Thêm lịch sử hội thoại (giới hạn 10 tin nhắn gần nhất)
            foreach (array_slice($conversationHistory, -10) as $msg) {
                $messages[] = $msg;
            }

            // Thêm tin nhắn hiện tại
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            // Log chi tiết lỗi
            $errorBody = $response->body();
            $errorData = $response->json();
            $errorMessage = $errorData['error']['message'] ?? $errorBody;
            
            Log::error('AI Chat Error', [
                'status' => $response->status(),
                'body' => $errorBody,
                'error' => $errorMessage,
            ]);

            // Nếu là lỗi quota, log cảnh báo
            if (stripos($errorMessage, 'quota') !== false || stripos($errorMessage, 'billing') !== false) {
                Log::warning('OpenAI API Quota Exceeded', [
                    'message' => 'API Key đã hết credit. Hệ thống sẽ tự động dùng Auto Reply.',
                ]);
            }

            return null;

        } catch (\Exception $e) {
            Log::error('AI Chat Exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * System prompt để AI hiểu vai trò của mình
     */
    protected function getSystemPrompt(): string
    {
        // Lấy thông tin cửa hàng từ cache hoặc config
        $storeInfo = Cache::remember('ai_store_info', 3600, function () {
            return config('services.openai.store_info', $this->getDefaultStoreInfo());
        });

        return $storeInfo;
    }

    /**
     * Thông tin mặc định về cửa hàng
     */
    protected function getDefaultStoreInfo(): string
    {
        return <<<PROMPT
Bạn là trợ lý ảo của AnhKiet Store - cửa hàng bán điện thoại, phụ kiện công nghệ uy tín tại Việt Nam.

THÔNG TIN CỬA HÀNG:
- Tên: AnhKiet Store
- Hotline: 1900.9999 (8:00 - 21:00 hàng ngày)
- Email: support@anhkiet.com
- Website: anhkiet.com
- Địa chỉ: 123 Nguyễn Huệ, Q.1, TP.HCM

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

