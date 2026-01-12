<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AnhKiet Store - Điện thoại & Phụ kiện')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Figtree', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .header {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-top {
            background: #f5f5f5;
            padding: 10px 0;
            font-size: 13px;
            border-bottom: 1px solid #e0e0e0;
        }
        .header-main {
            display: flex;
            align-items: center;
            padding: 15px 0;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #d32f2f;
            text-decoration: none;
        }
        .search-wrapper {
            flex: 1;
            margin: 0 20px;
            position: relative;
        }
        .search-box {
            display: flex;
        }
        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 4px 0 0 4px;
            font-size: 14px;
            outline: none;
        }
        .search-box input:focus {
            border-color: #d32f2f;
        }
        .search-box button {
            padding: 0 25px;
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: 600;
        }
        .search-suggestion-box {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 6px;
            background: #1f1f1f;
            color: #fff;
            border-radius: 6px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            padding: 10px 0;
            z-index: 1050;
            display: none;
        }
        .search-suggestion-item {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
        }
        .search-suggestion-item:hover {
            background: rgba(255,255,255,0.05);
        }
        .search-suggestion-title {
            padding: 6px 16px;
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.7;
        }
        .search-suggestion-divider {
            height: 1px;
            margin: 6px 0;
            background: rgba(255,255,255,0.1);
        }
        .header-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            color: #333;
            text-decoration: none;
            font-size: 11px;
        }
        .action-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }
        .action-item .badge {
            position: absolute;
            background: #d32f2f;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 20px;
        }
        .navbar {
            background: #d32f2f;
            color: white;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }
        .navbar li a {
            color: white;
            text-decoration: none;
            padding: 12px 0;
            display: block;
            font-weight: 500;
        }
        .navbar li a:hover {
            text-decoration: underline;
        }
        .main-content {
            min-height: calc(100vh - 300px);
        }
        .footer {
            background: #2c2c2c;
            color: #fff;
            padding: 40px 0 20px;
            margin-top: 50px;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        .footer-section h4 {
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        .footer-section ul {
            list-style: none;
        }
        .footer-section ul li {
            margin-bottom: 8px;
        }
        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
        }
        .footer-section ul li a:hover {
            color: #fff;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #444;
            color: #999;
            font-size: 13px;
        }
        .toast-message {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 2000;
            padding: 12px 18px;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        .toast-message.toast-success {
            background: #16a34a;
        }
        .toast-message.toast-error {
            background: #dc2626;
        }
        .toast-message.hide {
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header Top Bar -->
    <div class="header-top">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    Hotline: <strong>1900.9999</strong> | Miễn phí vận chuyển cho đơn hàng trên 500.000đ
                </div>
                <div>
                    @auth
                        <span style="margin-right: 15px;">Xin chào, <strong>{{ auth()->user()->name }}</strong></span>
                        <a href="{{ route('account.index') }}" style="color: #333; text-decoration: none; margin-right: 15px; font-weight: 600;">
                            <i class="fas fa-user-circle"></i> Tài khoản
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none;border:none;color:#333;cursor:pointer;text-decoration:underline;">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login.show') }}" style="color: #333; text-decoration: none; margin-right: 15px;">Đăng nhập</a>
                        <a href="{{ route('register.show') }}" style="color: #333; text-decoration: none;">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Header Main -->
    <div class="header">
        <div class="container">
            <div class="header-main">
                <a href="/" class="logo">
                    <i class="fas fa-mobile-alt"></i> AnhKiet Store
                </a>
                <div class="search-wrapper">
                    <form action="{{ route('search') }}" method="GET" class="search-box" autocomplete="off">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm điện thoại, phụ kiện..." id="main-search-input">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                    <div id="search-suggestion-box" class="search-suggestion-box"></div>
                </div>
                <div class="header-actions">
                    <a href="{{ auth()->check() ? route('wishlist.index') : '#' }}" class="action-item" @unless(auth()->check()) onclick="alert('Vui lòng đăng nhập để sử dụng danh sách yêu thích'); return false;" @endunless>
                        <i class="far fa-heart"></i>
                        <span>Yêu thích</span>
                    </a>
                    <a href="{{ route('cart.index') }}" class="action-item">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                        <span class="badge">{{ collect(session('cart', []))->sum('quantity') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        use App\Models\MenuItem;
        $topMenus = class_exists(MenuItem::class)
            ? MenuItem::whereNull('parent_id')->where('is_active', true)->orderBy('order')->get()
            : collect();
    @endphp

    <div class="navbar">
        <div class="container">
            <ul>
                @forelse($topMenus as $m)
                    <li><a href="{{ $m->url }}">{{ $m->title }}</a></li>
                @empty
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="{{ route('category.index') }}">Danh mục</a></li>
                    <li><a href="{{ route('sale.index') }}"><i class="fas fa-fire"></i> Khuyến mãi</a></li>
                    <li><a href="{{ route('posts.index') }}">Tin tức</a></li>
                @endforelse
            </ul>
        </div>
    </div>

    @if (session('success') || session('error'))
        <div id="global-toast"
             class="toast-message {{ session('success') ? 'toast-success' : 'toast-error' }}">
            <i class="fas {{ session('success') ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
            <span>{{ session('success') ?? session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Giới thiệu</h4>
                    <ul>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Hệ thống cửa hàng</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Hỗ trợ khách hàng</h4>
                    <ul>
                        <li><a href="#">Trung tâm trợ giúp</a></li>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                        <li><a href="#">Phương thức vận chuyển</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Kết nối</h4>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i> YouTube</a></li>
                        <li><a href="#"><i class="fab fa-tiktok"></i> TikTok</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Đăng ký nhận tin</h4>
                    <p style="color: #ccc; margin-bottom: 15px;">Nhận thông tin sản phẩm mới nhất và khuyến mãi độc quyền</p>
                    <div class="search-box">
                        <input type="email" placeholder="Email của bạn">
                        <button>Gửi</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 AnhKiet Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            const input = document.getElementById('main-search-input');
            const box = document.getElementById('search-suggestion-box');
            const form = input ? input.closest('form') : null;
            if (!input || !box || !form) return;

            let timer = null;

            function hideBox() {
                box.style.display = 'none';
            }

            function showBox() {
                if (box.innerHTML.trim() !== '') {
                    box.style.display = 'block';
                }
            }

            function renderSuggestions(data) {
                const { recent_searches = [], products = [] } = data || {};
                let html = '';

                if (recent_searches.length) {
                    html += '<div class="search-suggestion-title">Lịch sử tìm kiếm</div>';
                    recent_searches.forEach(function (keyword) {
                        html += '<div class="search-suggestion-item" data-keyword=\"' + keyword.replace(/"/g, '&quot;') + '\">' + keyword + '</div>';
                    });
                }

                if (products.length) {
                    if (html) {
                        html += '<div class="search-suggestion-divider"></div>';
                    }
                    html += '<div class="search-suggestion-title">Gợi ý cho bạn</div>';
                    products.forEach(function (p) {
                        html += '<div class="search-suggestion-item" data-slug=\"' + p.slug + '\">' + p.name + '</div>';
                    });
                }

                box.innerHTML = html;
                if (html) {
                    showBox();
                } else {
                    hideBox();
                }
            }

            function fetchSuggestions() {
                const q = input.value || '';
                const url = '{{ route('search.suggestions') }}' + '?q=' + encodeURIComponent(q);

                fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(function (res) { return res.json(); })
                    .then(renderSuggestions)
                    .catch(function () { hideBox(); });
            }

            input.addEventListener('focus', function () {
                fetchSuggestions();
            });

            input.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(fetchSuggestions, 250);
            });

            input.addEventListener('blur', function () {
                setTimeout(hideBox, 150);
            });

            box.addEventListener('mousedown', function (e) {
                const item = e.target.closest('.search-suggestion-item');
                if (!item) return;

                const keyword = item.getAttribute('data-keyword');
                const slug = item.getAttribute('data-slug');

                if (keyword) {
                    input.value = keyword;
                    form.submit();
                } else if (slug) {
                    window.location.href = '{{ url('/san-pham') }}/' + slug;
                }
            });
        })();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toast = document.getElementById('global-toast');
            if (!toast) return;
            setTimeout(function () {
                toast.classList.add('hide');
            }, 2800);
        });
    </script>

    @stack('scripts')

    <!-- Chat Widget -->
    @include('components.chat-widget')
</body>
</html>

