<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - AnhKiet Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; overflow-x: hidden; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #2c3e50; color: white; min-height: 100vh; position: fixed; left: 0; top: 0; overflow-y: auto; overflow-x: hidden; z-index: 1000; height: 100vh; }
        .sidebar-header { padding: 20px; background: #1a252f; text-align: center; }
        .sidebar-header h2 { font-size: 24px; }
        .sidebar-menu { list-style: none; }
        .sidebar-menu li a { display: block; padding: 15px 20px; color: white; text-decoration: none; transition: background 0.3s; }
        .sidebar-menu li a:hover { background: #34495e; }
        .sidebar-menu li a.active { background: #3498db; }
        .sidebar-menu li a i { width: 25px; margin-right: 10px; }
        .main-content { margin-left: 260px; padding: 30px; width: calc(100% - 260px); min-height: 100vh; overflow-y: auto; overflow-x: hidden; }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .page-header h1 { font-size: 28px; color: #2c3e50; }
        .btn-primary { background: #3498db; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; }
        .btn-primary:hover { background: #2980b9; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .table-section { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        table th { background: #ecf0f1; font-weight: 600; }
        .btn-edit, .btn-delete { padding: 5px 10px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .btn-edit { background: #3498db; color: white; }
        .btn-delete { background: #e74c3c; color: white; border: none; cursor: pointer; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #2ecc71; color: white; }
        .badge-danger { background: #e74c3c; color: white; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination ul { display: inline-flex; list-style: none; gap: 5px; padding: 0; margin: 0; }
        .pagination li { display: inline-block; }
        .pagination a, .pagination span { display: inline-block; padding: 6px 10px !important; text-decoration: none; border: 1px solid #ddd; border-radius: 4px; color: #333 !important; font-size: 13px !important; min-width: auto !important; height: auto !important; line-height: 1.4 !important; }
        .pagination a:hover { background: #3498db; color: white !important; border-color: #3498db; }
        .pagination .active span { background: #3498db; color: white !important; border-color: #3498db; }
        .pagination .disabled span { color: #ccc !important; cursor: not-allowed; background: #f5f5f5; }
        .pagination svg { width: 14px !important; height: 14px !important; }
        /* Toggle switch */
        .switch { position: relative; display: inline-block; width: 46px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .switch .slider { position: absolute; cursor: pointer; inset: 0; background: #bdc3c7; transition: .2s; border-radius: 9999px; }
        .switch .slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 2px; top: 2px; background: white; transition: .2s; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,.2); }
        .switch input:checked + .slider { background: #3498db; }
        .switch input:checked + .slider:before { transform: translateX(20px); }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-tachometer-alt"></i> Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
                <li><a href="{{ route('admin.orders.statistics') }}"><i class="fas fa-chart-bar"></i> Thống kê</a></li>
                
                <li style="margin-top: 10px; padding: 10px 20px; color: #95a5a6; font-size: 12px; font-weight: 600; text-transform: uppercase; border-top: 1px solid rgba(255,255,255,0.1);">Sản phẩm</li>
                <li><a href="{{ route('products.index') }}"><i class="fas fa-box"></i> Sản phẩm</a></li>
                <li><a href="{{ route('products.inventory') }}"><i class="fas fa-warehouse"></i> Quản lý kho</a></li>
                <li><a href="{{ route('sale-products.index') }}"><i class="fas fa-fire"></i> Quản lý Sale</a></li>
                <li><a href="{{ route('brands.index') }}"><i class="fas fa-tags"></i> Thương hiệu</a></li>
                <li><a href="{{ route('categories.index') }}"><i class="fas fa-list"></i> Danh mục</a></li>
                
                <li style="margin-top: 10px; padding: 10px 20px; color: #95a5a6; font-size: 12px; font-weight: 600; text-transform: uppercase; border-top: 1px solid rgba(255,255,255,0.1);">Nội dung</li>
                <li><a href="{{ route('banners.index') }}"><i class="fas fa-image"></i> Banners</a></li>
                <li><a href="{{ route('coupons.index') }}"><i class="fas fa-ticket"></i> Khuyến mãi</a></li>
                <li><a href="{{ route('post-categories.index') }}"><i class="fas fa-folder"></i> Chủ đề bài viết</a></li>
                <li><a href="{{ route('posts.admin.index') }}"><i class="fas fa-newspaper"></i> Bài viết</a></li>
                <li><a href="{{ route('menus.index') }}"><i class="fas fa-bars"></i> Menu</a></li>
                <li><a href="{{ route('admin.reviews.index') }}"><i class="fas fa-star"></i> Quản lý đánh giá</a></li>
                
                <li style="margin-top: 10px; padding: 10px 20px; color: #95a5a6; font-size: 12px; font-weight: 600; text-transform: uppercase; border-top: 1px solid rgba(255,255,255,0.1);">Hỗ trợ & Chat</li>
                <li><a href="{{ route('admin.messages.index') }}"><i class="fas fa-comments"></i> Tin nhắn</a></li>
                <li><a href="{{ route('admin.auto-replies.index') }}"><i class="fas fa-robot"></i> Chat tự động</a></li>
                <li><a href="{{ route('admin.ai-settings.index') }}"><i class="fas fa-brain"></i> AI Chat</a></li>
                
                <li style="margin-top: 10px; padding: 10px 20px; color: #95a5a6; font-size: 12px; font-weight: 600; text-transform: uppercase; border-top: 1px solid rgba(255,255,255,0.1);">Khác</li>
                <li><a href="{{ route('home') }}" target="_blank"><i class="fas fa-globe"></i> Xem trang chủ</a></li>
                <li><a href="#"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>
        </aside>

        <main class="main-content">
            @yield('content')
            @stack('scripts')
        </main>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tokenTag = document.querySelector('meta[name="csrf-token"]');
    if (!tokenTag) return;
    const csrfToken = tokenTag.getAttribute('content');
    document.body.addEventListener('change', async function (e) {
        const target = e.target;
        if (target && target.classList.contains('toggle-status')) {
            const url = target.getAttribute('data-url');
            const previous = !target.checked;
            try {
                const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
                if (!res.ok) throw new Error();
            } catch (err) {
                target.checked = previous;
                alert('Cập nhật trạng thái thất bại.');
            }
        }
    });
});
</script>
</html>

