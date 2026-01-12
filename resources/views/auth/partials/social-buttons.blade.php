<div style="margin-top: 25px;">
    <p style="text-align: center; color: #999; font-size: 13px; margin-bottom: 12px;">Hoặc tiếp tục với</p>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('social.redirect', ['provider' => 'google']) }}" style="flex: 1; min-width: 120px; text-align: center; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 4px; color: #444; text-decoration: none; font-weight: 600;">
            Google
        </a>
        <a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" style="flex: 1; min-width: 120px; text-align: center; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 4px; color: #1877f2; text-decoration: none; font-weight: 600;">
            Facebook
        </a>
        <a href="{{ route('social.redirect', ['provider' => 'zalo']) }}" style="flex: 1; min-width: 120px; text-align: center; padding: 10px 12px; border: 1px solid #e0e0e0; border-radius: 4px; color: #0068ff; text-decoration: none; font-weight: 600;">
            Zalo
        </a>
    </div>
</div>

