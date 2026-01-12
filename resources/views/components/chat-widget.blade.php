<!-- Chat Widget -->
<div id="chat-widget" style="position:fixed;bottom:20px;right:20px;z-index:9999;">
    <!-- Chat Button -->
    <button id="chat-toggle" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#d32f2f,#b71c1c);color:#fff;border:none;box-shadow:0 4px 15px rgba(211,47,47,0.4);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:24px;transition:transform 0.3s,box-shadow 0.3s;">
        <i class="fas fa-comments"></i>
    </button>
    <!-- Notification Badge -->
    <span id="chat-badge" style="display:none;position:absolute;top:-5px;right:-5px;background:#ff9800;color:#fff;width:20px;height:20px;border-radius:50%;font-size:11px;font-weight:600;display:flex;align-items:center;justify-content:center;">1</span>

    <!-- Chat Box -->
    <div id="chat-box" style="display:none;position:absolute;bottom:80px;right:0;width:380px;max-width:90vw;background:#fff;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.25);overflow:hidden;">
        <!-- Chat Header -->
        <div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:#fff;padding:18px 20px;display:flex;justify-content:space-between;align-items:center;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-headset" style="font-size:18px;"></i>
                </div>
                <div>
                    <strong style="font-size:15px;display:block;">Hỗ trợ khách hàng</strong>
                    <div style="font-size:12px;opacity:0.85;display:flex;align-items:center;gap:4px;">
                        <span style="width:8px;height:8px;background:#4caf50;border-radius:50%;"></span> Online
                    </div>
                </div>
            </div>
            <button id="chat-close" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;transition:background 0.2s;">&times;</button>
        </div>

        <!-- Chat Messages Area -->
        <div id="chat-messages" style="height:280px;overflow-y:auto;padding:16px;background:#f5f5f5;">
            <!-- Welcome message -->
            <div class="bot-message" style="display:flex;gap:10px;margin-bottom:12px;">
                <div style="width:32px;height:32px;background:#d32f2f;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-robot" style="color:#fff;font-size:14px;"></i>
                </div>
                <div style="background:#fff;padding:12px 14px;border-radius:0 12px 12px 12px;box-shadow:0 1px 2px rgba(0,0,0,0.1);max-width:85%;">
                    <p style="margin:0;font-size:14px;line-height:1.5;">Xin chào! 👋 Tôi là trợ lý ảo của AnhKiet Store. Bạn cần hỗ trợ gì?</p>
                </div>
            </div>

            <!-- FAQ Suggestions -->
            <!-- FAQ Suggestions -->
            <div id="chat-faq" style="margin-bottom:12px;">
                <p style="font-size:12px;color:#666;margin-bottom:8px;">💡 Câu hỏi thường gặp:</p>
                <div id="faq-buttons" style="display:flex;flex-wrap:wrap;gap:6px;">
                    <!-- FAQ buttons will be loaded here -->
                </div>
            </div>
        </div>

        <!-- AI Status indicator -->
        @if(config('services.openai.enabled'))
        <div id="ai-status" style="padding:6px 16px;background:#e8f5e9;border-bottom:1px solid #c8e6c9;display:flex;align-items:center;gap:8px;font-size:12px;color:#2e7d32;">
            <i class="fas fa-brain"></i>
            <span>Hỗ trợ bởi AI - Trả lời thông minh 24/7</span>
        </div>
        @endif

        <!-- User Info Form (initially shown) -->
        <div id="chat-info-form" style="padding:16px;border-top:1px solid #eee;background:#fff;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                <input type="text" id="chat-name" placeholder="Họ tên *" required
                       value="{{ auth()->user()->name ?? '' }}"
                       style="padding:10px 12px;border:1px solid #e0e0e0;border-radius:8px;font-size:13px;">
                <input type="email" id="chat-email" placeholder="Email *" required
                       value="{{ auth()->user()->email ?? '' }}"
                       style="padding:10px 12px;border:1px solid #e0e0e0;border-radius:8px;font-size:13px;">
            </div>
            <input type="text" id="chat-phone" placeholder="Số điện thoại (tùy chọn)"
                   style="width:100%;padding:10px 12px;border:1px solid #e0e0e0;border-radius:8px;font-size:13px;margin-bottom:10px;">
        </div>

        <!-- Message Input -->
        <div style="padding:12px 16px;border-top:1px solid #eee;background:#fff;">
            <form id="chat-form" style="display:flex;gap:10px;align-items:center;">
                <input type="text" id="chat-message" placeholder="Nhập tin nhắn..." required
                       style="flex:1;padding:12px 14px;border:1px solid #e0e0e0;border-radius:24px;font-size:14px;outline:none;transition:border-color 0.2s;">
                <button type="submit" id="chat-submit"
                        style="width:44px;height:44px;background:linear-gradient(135deg,#d32f2f,#b71c1c);color:#fff;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:transform 0.2s;">
                    <i class="fas fa-paper-plane" style="font-size:16px;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
#chat-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(211,47,47,0.5);
}
#chat-close:hover {
    background: rgba(255,255,255,0.25);
}
#chat-message:focus {
    border-color: #d32f2f;
}
#chat-submit:hover {
    transform: scale(1.05);
}
#chat-messages::-webkit-scrollbar {
    width: 6px;
}
#chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}
#chat-messages::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}
.faq-btn {
    background: #fff;
    border: 1px solid #d32f2f;
    color: #d32f2f;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}
.faq-btn:hover {
    background: #d32f2f;
    color: #fff;
}
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 8px 12px;
}
.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-8px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chat-toggle');
    const chatBox = document.getElementById('chat-box');
    const closeBtn = document.getElementById('chat-close');
    const chatForm = document.getElementById('chat-form');
    const chatMessages = document.getElementById('chat-messages');
    const messageInput = document.getElementById('chat-message');
    const faqButtons = document.getElementById('faq-buttons');

    // Load FAQ on widget open
    let faqLoaded = false;

    toggleBtn.addEventListener('click', function() {
        const isHidden = chatBox.style.display === 'none';
        chatBox.style.display = isHidden ? 'block' : 'none';
        
        if (isHidden && !faqLoaded) {
            loadFaq();
            faqLoaded = true;
        }
    });

    closeBtn.addEventListener('click', function() {
        chatBox.style.display = 'none';
    });

    // Load FAQ suggestions
    function loadFaq() {
        fetch('{{ route("chat.faq") }}')
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    faqButtons.innerHTML = data.map(faq => 
                        `<button type="button" class="faq-btn" data-response="${escapeHtml(faq.response)}">${escapeHtml(faq.question)}</button>`
                    ).join('');
                    
                    // Add click handlers to FAQ buttons
                    faqButtons.querySelectorAll('.faq-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const question = this.textContent;
                            const response = this.getAttribute('data-response');
                            
                            // Show user question
                            addUserMessage(question);
                            
                            // Show bot response after a short delay
                            showTypingIndicator();
                            setTimeout(() => {
                                removeTypingIndicator();
                                addBotMessage(response);
                            }, 800);
                        });
                    });
                }
            })
            .catch(err => console.log('FAQ load error:', err));
    }

    // Submit message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('chat-name').value.trim();
        const email = document.getElementById('chat-email').value.trim();
        const phone = document.getElementById('chat-phone').value.trim();
        const message = messageInput.value.trim();

        if (!name || !email) {
            alert('Vui lòng nhập họ tên và email.');
            return;
        }

        if (!message) return;

        // Show user message
        addUserMessage(message);
        messageInput.value = '';

        // Hide info form after first message
        document.getElementById('chat-info-form').style.display = 'none';

        // Show typing indicator
        showTypingIndicator();

        // Send to server
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('message', message);

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            removeTypingIndicator();
            
            if (data.auto_reply) {
                // Show auto reply (with AI indicator if applicable)
                addBotMessage(data.auto_reply.message, data.is_ai || false);
            } else {
                // Show default response
                addBotMessage('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất. 🙏', false);
            }
        })
        .catch(error => {
            removeTypingIndicator();
            addBotMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.');
        });
    });

    function addUserMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.style.cssText = 'display:flex;justify-content:flex-end;margin-bottom:12px;';
        msgDiv.innerHTML = `
            <div style="background:linear-gradient(135deg,#d32f2f,#b71c1c);color:#fff;padding:12px 14px;border-radius:12px 0 12px 12px;max-width:85%;font-size:14px;line-height:1.5;">
                ${escapeHtml(text)}
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function addBotMessage(text, isAI = false) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'bot-message';
        msgDiv.style.cssText = 'display:flex;gap:10px;margin-bottom:12px;';
        
        // Format text với line breaks
        const formattedText = escapeHtml(text).replace(/\\n/g, '<br>').replace(/\n/g, '<br>');
        
        msgDiv.innerHTML = `
            <div style="width:32px;height:32px;background:${isAI ? 'linear-gradient(135deg,#667eea,#764ba2)' : '#d32f2f'};border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas ${isAI ? 'fa-brain' : 'fa-robot'}" style="color:#fff;font-size:14px;"></i>
            </div>
            <div style="background:#fff;padding:12px 14px;border-radius:0 12px 12px 12px;box-shadow:0 1px 2px rgba(0,0,0,0.1);max-width:85%;font-size:14px;line-height:1.5;">
                ${formattedText}
                ${isAI ? '<div style="font-size:10px;color:#764ba2;margin-top:4px;"><i class="fas fa-sparkles"></i> AI</div>' : ''}
            </div>
        `;
        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const typing = document.createElement('div');
        typing.id = 'typing-indicator';
        typing.className = 'bot-message';
        typing.style.cssText = 'display:flex;gap:10px;margin-bottom:12px;';
        typing.innerHTML = `
            <div style="width:32px;height:32px;background:#d32f2f;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-robot" style="color:#fff;font-size:14px;"></i>
            </div>
            <div style="background:#fff;padding:10px 14px;border-radius:0 12px 12px 12px;box-shadow:0 1px 2px rgba(0,0,0,0.1);">
                <div class="typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>
        `;
        chatMessages.appendChild(typing);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('typing-indicator');
        if (typing) typing.remove();
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
