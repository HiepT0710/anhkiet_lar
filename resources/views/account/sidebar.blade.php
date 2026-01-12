<div>
    <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 32px; margin: 0 auto 15px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">{{ auth()->user()->name }}</div>
            <div style="font-size: 13px; color: #999;">{{ auth()->user()->email }}</div>
        </div>
        <nav style="display: flex; flex-direction: column; gap: 5px;">
            <a href="{{ route('account.index') }}" style="padding: 12px 15px; border-radius: 8px; color: #2c3e50; text-decoration: none; {{ request()->routeIs('account.index') ? 'background: #f8f9fa; font-weight: 600;' : '' }}">
                <i class="fas fa-home"></i> Tổng quan
            </a>
            <a href="{{ route('account.profile') }}" style="padding: 12px 15px; border-radius: 8px; color: #2c3e50; text-decoration: none; {{ request()->routeIs('account.profile') ? 'background: #f8f9fa; font-weight: 600;' : '' }}">
                <i class="fas fa-user"></i> Thông tin cá nhân
            </a>
            <a href="{{ route('account.orders') }}" style="padding: 12px 15px; border-radius: 8px; color: #2c3e50; text-decoration: none; {{ request()->routeIs('account.orders*') ? 'background: #f8f9fa; font-weight: 600;' : '' }}">
                <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
            </a>
            <a href="{{ route('account.reviews') }}" style="padding: 12px 15px; border-radius: 8px; color: #2c3e50; text-decoration: none; {{ request()->routeIs('account.reviews') ? 'background: #f8f9fa; font-weight: 600;' : '' }}">
                <i class="fas fa-star"></i> Đánh giá của tôi
            </a>
            <a href="{{ route('wishlist.index') }}" style="padding: 12px 15px; border-radius: 8px; color: #2c3e50; text-decoration: none;">
                <i class="fas fa-heart"></i> Yêu thích
            </a>
        </nav>
    </div>
</div>

