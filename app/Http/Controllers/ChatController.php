<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\AutoReply;
use App\Services\AIChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    protected AIChatService $aiService;

    public function __construct(AIChatService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        // Lưu tin nhắn của khách hàng
        $userMessage = Message::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        $autoReplyMessage = null;
        $responseText = null;
        $isAiResponse = false;

        // 1. Thử dùng AI trước (nếu được bật)
        if ($this->aiService->isEnabled()) {
            // Lấy lịch sử hội thoại từ session
            $sessionKey = 'chat_history_' . ($request->session()->getId());
            $conversationHistory = Cache::get($sessionKey, []);

            // Gọi AI
            $responseText = $this->aiService->chat($validated['message'], $conversationHistory);

            if ($responseText) {
                $isAiResponse = true;

                // Lưu lịch sử hội thoại
                $conversationHistory[] = ['role' => 'user', 'content' => $validated['message']];
                $conversationHistory[] = ['role' => 'assistant', 'content' => $responseText];
                
                // Giữ tối đa 20 tin nhắn trong history
                if (count($conversationHistory) > 20) {
                    $conversationHistory = array_slice($conversationHistory, -20);
                }
                
                Cache::put($sessionKey, $conversationHistory, 3600); // 1 giờ
            }
        }

        // 2. Nếu AI không trả lời, dùng auto-reply theo từ khóa
        if (!$responseText) {
            $autoReply = AutoReply::findMatch($validated['message']);
            if ($autoReply) {
                $responseText = $autoReply->response;
            }
        }

        // 3. Tạo tin nhắn phản hồi nếu có
        if ($responseText) {
            $autoReplyMessage = Message::create([
                'user_id' => null,
                'name' => $isAiResponse ? 'AI Assistant' : 'Hệ thống tự động',
                'email' => 'system@anhkiet.com',
                'phone' => null,
                'message' => $responseText,
                'status' => 'replied',
                'is_auto_reply' => true,
                'parent_id' => $userMessage->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tin nhắn của bạn đã được gửi!',
            'user_message' => $userMessage,
            'auto_reply' => $autoReplyMessage,
            'is_ai' => $isAiResponse,
        ]);
    }

    /**
     * Lấy danh sách câu hỏi thường gặp (FAQ) để hiển thị nhanh
     */
    public function getFaq()
    {
        $faqs = AutoReply::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                // Lấy từ khóa đầu tiên làm câu hỏi gợi ý
                $keywords = explode(',', $item->keywords);
                return [
                    'question' => trim($keywords[0]),
                    'response' => $item->response,
                ];
            });

        return response()->json($faqs);
    }

    public function index(Request $request)
    {
        $messages = Message::with(['user', 'repliedBy'])
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        $message->load(['user', 'repliedBy']);

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:2000',
        ]);

        $message->update([
            'admin_reply' => $validated['admin_reply'],
            'status' => 'replied',
            'replied_by' => Auth::id(),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.messages.show', $message)
            ->with('success', 'Đã gửi phản hồi thành công!');
    }

    public function markAsRead(Message $message)
    {
        $message->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }
}

