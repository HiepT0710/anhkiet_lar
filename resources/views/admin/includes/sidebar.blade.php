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
















