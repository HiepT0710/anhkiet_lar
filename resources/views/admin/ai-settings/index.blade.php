@extends('layouts.admin')

@section('title', 'Cài đặt AI Chat')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-brain"></i> Cài đặt AI Chat</h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <!-- Trạng thái AI -->
    <div class="table-section">
        <h3 style="margin-bottom:20px;color:#2c3e50;"><i class="fas fa-cog"></i> Trạng thái kết nối</h3>
        
        <div style="padding:20px;background:{{ $settings['enabled'] ? '#e8f5e9' : '#fff3e0' }};border-radius:8px;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:12px;height:12px;border-radius:50%;background:{{ $settings['enabled'] ? '#4caf50' : '#ff9800' }};"></div>
                <strong style="font-size:16px;">{{ $settings['enabled'] ? 'AI đang hoạt động' : 'AI chưa được kích hoạt' }}</strong>
            </div>
            @if($settings['enabled'])
                <p style="margin:0;color:#2e7d32;">Model: <strong>{{ $settings['model'] }}</strong></p>
                <p style="margin:4px 0 0;color:#2e7d32;">API Key: <strong>{{ $settings['api_key'] }}</strong></p>
            @else
                <p style="margin:0;color:#e65100;">Thêm API Key vào file <code>.env</code> để kích hoạt AI</p>
            @endif
        </div>

        <div style="background:#f5f5f5;padding:16px;border-radius:8px;">
            <h4 style="margin-bottom:12px;">📝 Cách cấu hình:</h4>
            <p style="font-size:14px;margin-bottom:8px;">Thêm các dòng sau vào file <code>.env</code>:</p>
            <pre style="background:#2c3e50;color:#ecf0f1;padding:12px;border-radius:6px;font-size:13px;overflow-x:auto;">OPENAI_ENABLED=true
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-3.5-turbo</pre>
            <p style="font-size:13px;color:#666;margin-top:12px;">
                <i class="fas fa-info-circle"></i> Lấy API Key tại: <a href="https://platform.openai.com/api-keys" target="_blank">platform.openai.com</a>
            </p>
        </div>

        @if($settings['enabled'])
        <div style="background:#fff3cd;border-left:4px solid #ffc107;padding:16px;border-radius:8px;margin-top:16px;">
            <strong style="color:#856404;"><i class="fas fa-exclamation-triangle"></i> Lưu ý về Quota/Credit:</strong>
            <ul style="margin:8px 0 0 20px;font-size:13px;color:#856404;">
                <li>OpenAI API tính phí theo số lượng tin nhắn (~$0.001-0.003/tin nhắn)</li>
                <li>Nếu hết credit, bạn sẽ thấy lỗi <strong>"quota exceeded"</strong></li>
                <li>Kiểm tra credit tại: <a href="https://platform.openai.com/account/billing" target="_blank">platform.openai.com/account/billing</a></li>
                <li>Khi AI không hoạt động, hệ thống sẽ <strong>tự động dùng Chat tự động</strong> làm backup</li>
            </ul>
        </div>
        @endif

        <!-- Test Connection -->
        <div style="margin-top:20px;">
            <h4 style="margin-bottom:12px;"><i class="fas fa-network-wired"></i> Test kết nối</h4>
            <button type="button" id="test-connection-btn" class="btn-primary" {{ !$settings['enabled'] ? 'disabled' : '' }} style="margin-bottom:12px;">
                <i class="fas fa-plug"></i> Kiểm tra kết nối API
            </button>
            <div id="test-connection-result" style="margin-top:12px;padding:12px;background:#f5f5f5;border-radius:6px;display:none;"></div>
        </div>

        <!-- Test AI -->
        <div style="margin-top:20px;">
            <h4 style="margin-bottom:12px;"><i class="fas fa-flask"></i> Test AI</h4>
            <div style="display:flex;gap:10px;">
                <input type="text" id="test-message" placeholder="Nhập tin nhắn test..." 
                       style="flex:1;padding:10px 12px;border:1px solid #ddd;border-radius:6px;">
                <button type="button" id="test-btn" class="btn-primary" {{ !$settings['enabled'] ? 'disabled' : '' }}>
                    <i class="fas fa-paper-plane"></i> Gửi
                </button>
            </div>
            <div id="test-result" style="margin-top:12px;padding:12px;background:#f5f5f5;border-radius:6px;display:none;"></div>
        </div>
    </div>

    <!-- Thông tin cửa hàng -->
    <div class="table-section">
        <h3 style="margin-bottom:20px;color:#2c3e50;"><i class="fas fa-store"></i> Thông tin cho AI</h3>
        <p style="color:#666;margin-bottom:16px;font-size:14px;">
            Nhập thông tin về cửa hàng để AI có thể trả lời chính xác. Đây là "system prompt" giúp AI hiểu vai trò và thông tin của cửa hàng.
        </p>
        
        <form action="{{ route('admin.ai-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <textarea name="store_info" rows="20" 
                      style="width:100%;padding:12px;border:1px solid #ddd;border-radius:6px;font-size:13px;font-family:monospace;resize:vertical;">{{ $storeInfo }}</textarea>
            
            @error('store_info')
            <div style="color:#e74c3c;font-size:13px;margin-top:4px;">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn-primary" style="margin-top:16px;">
                <i class="fas fa-save"></i> Lưu thông tin
            </button>
        </form>
    </div>
</div>

<!-- Hướng dẫn sử dụng -->
<div class="table-section" style="margin-top:24px;">
    <h3 style="margin-bottom:16px;color:#2c3e50;"><i class="fas fa-lightbulb"></i> Cách hoạt động</h3>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
        <div style="padding:20px;background:#e3f2fd;border-radius:8px;">
            <div style="font-size:24px;margin-bottom:8px;">1️⃣</div>
            <strong>Khách gửi tin nhắn</strong>
            <p style="font-size:13px;color:#666;margin-top:8px;">Khách hàng gửi câu hỏi qua chatbox trên website</p>
        </div>
        <div style="padding:20px;background:#e8f5e9;border-radius:8px;">
            <div style="font-size:24px;margin-bottom:8px;">2️⃣</div>
            <strong>AI xử lý</strong>
            <p style="font-size:13px;color:#666;margin-top:8px;">AI đọc thông tin cửa hàng + câu hỏi → Tạo câu trả lời phù hợp</p>
        </div>
        <div style="padding:20px;background:#fff3e0;border-radius:8px;">
            <div style="font-size:24px;margin-bottom:8px;">3️⃣</div>
            <strong>Phản hồi tự động</strong>
            <p style="font-size:13px;color:#666;margin-top:8px;">Khách nhận được câu trả lời ngay lập tức 24/7</p>
        </div>
    </div>
    
    <div style="margin-top:20px;padding:16px;background:#fce4ec;border-radius:8px;">
        <strong style="color:#c2185b;"><i class="fas fa-exclamation-triangle"></i> Lưu ý:</strong>
        <ul style="margin:8px 0 0 20px;font-size:14px;color:#666;">
            <li>Nếu AI không được bật hoặc lỗi, hệ thống sẽ dùng <strong>Chat tự động theo từ khóa</strong> làm backup</li>
            <li>Mỗi tin nhắn AI sẽ tốn một ít credit từ OpenAI (~$0.001-0.003/tin nhắn)</li>
            <li>Tin nhắn của khách vẫn được lưu lại để admin xem và phản hồi thủ công nếu cần</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
// Test Connection
document.getElementById('test-connection-btn')?.addEventListener('click', function() {
    const resultDiv = document.getElementById('test-connection-result');
    
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...';
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra kết nối...';
    
    fetch('{{ route("admin.ai-settings.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<strong style="color:#2e7d32;">✅ ' + data.message + '</strong>';
            resultDiv.style.background = '#e8f5e9';
        } else {
            let errorMsg = data.message;
            // Highlight quota error
            if (errorMsg.includes('quota') || errorMsg.includes('billing')) {
                errorMsg += '<br><br><strong>💡 Giải pháp:</strong><br>';
                errorMsg += '1. Kiểm tra credit tại <a href="https://platform.openai.com/account/billing" target="_blank">platform.openai.com/account/billing</a><br>';
                errorMsg += '2. Nạp thêm credit vào tài khoản OpenAI<br>';
                errorMsg += '3. Hoặc hệ thống sẽ tự động dùng <strong>Chat tự động</strong> làm backup';
            }
            resultDiv.innerHTML = '<strong style="color:#c62828;">❌ ' + errorMsg + '</strong>';
            resultDiv.style.background = '#fdecea';
        }
    })
    .catch(err => {
        resultDiv.innerHTML = '<strong style="color:#c62828;">❌ Lỗi kết nối: ' + err.message + '</strong>';
        resultDiv.style.background = '#fdecea';
    })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-plug"></i> Kiểm tra kết nối API';
    });
});

// Test AI
document.getElementById('test-btn')?.addEventListener('click', function() {
    const message = document.getElementById('test-message').value.trim();
    const resultDiv = document.getElementById('test-result');
    
    if (!message) {
        alert('Vui lòng nhập tin nhắn test');
        return;
    }
    
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    resultDiv.style.display = 'block';
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang chờ AI phản hồi...';
    
    fetch('{{ route("admin.ai-settings.test") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = '<strong style="color:#2e7d32;">✅ AI Response:</strong><br>' + data.response.replace(/\n/g, '<br>');
            resultDiv.style.background = '#e8f5e9';
        } else {
            let errorMsg = data.message;
            // Highlight quota error
            if (errorMsg.includes('quota') || errorMsg.includes('billing')) {
                errorMsg += '<br><br><strong>💡 Giải pháp:</strong><br>';
                errorMsg += '1. Kiểm tra credit tại <a href="https://platform.openai.com/account/billing" target="_blank">platform.openai.com/account/billing</a><br>';
                errorMsg += '2. Nạp thêm credit vào tài khoản OpenAI<br>';
                errorMsg += '3. Hoặc hệ thống sẽ tự động dùng <strong>Chat tự động</strong> làm backup';
            }
            resultDiv.innerHTML = '<strong style="color:#c62828;">❌ Lỗi:</strong> ' + errorMsg;
            resultDiv.style.background = '#fdecea';
        }
    })
    .catch(err => {
        resultDiv.innerHTML = '<strong style="color:#c62828;">❌ Lỗi kết nối</strong>';
        resultDiv.style.background = '#fdecea';
    })
    .finally(() => {
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi';
    });
});
</script>
@endpush
@endsection

