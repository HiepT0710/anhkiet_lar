@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 40px 0;">
    <div class="container">
        <div style="max-width: 450px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h2 style="text-align: center; margin-bottom: 10px; color: #333;">Đăng nhập</h2>
            <p style="text-align: center; color: #666; margin-bottom: 30px;">Chào mừng trở lại!</p>

            @if ($errors->any())
                <div style="background: #fee; color: #c33; padding: 15px; border-radius: 4px; margin-bottom: 20px; font-size: 14px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                        placeholder="email@example.com">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Mật khẩu</label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;"
                        placeholder="Nhập mật khẩu">
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" name="remember" style="margin-right: 8px;">
                        <span style="font-size: 14px;">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="#" style="font-size: 14px; color: #d32f2f; text-decoration: none;">Quên mật khẩu?</a>
                </div>

                <button type="submit" 
                    style="width: 100%; padding: 14px; background: #d32f2f; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: 600; cursor: pointer;">
                    Đăng nhập
                </button>

                @include('auth.partials.social-buttons')

                <p style="text-align: center; margin-top: 25px; font-size: 14px; color: #666;">
                    Chưa có tài khoản? <a href="{{ route('register.show') }}" style="color: #d32f2f; text-decoration: none; font-weight: 500;">Đăng ký ngay</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

