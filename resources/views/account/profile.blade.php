@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        @include('account.sidebar')

        <div>
            <h1 style="margin-bottom: 30px; color: #2c3e50;">Thông tin cá nhân</h1>

            @if(session('success'))
            <div style="background: #e8f5e9; color: #2e7d32; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div style="background: #ffebee; color: #c62828; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Thông tin cá nhân -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Cập nhật thông tin</h2>
                <form method="POST" action="{{ route('account.profile.update') }}">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Họ và tên *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <button type="submit" style="background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Đổi mật khẩu -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h2 style="margin: 0 0 20px 0; color: #2c3e50;">Đổi mật khẩu</h2>
                <form method="POST" action="{{ route('account.password.update') }}">
                    @csrf
                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" required
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Mật khẩu mới *</label>
                            <input type="password" name="password" required minlength="8"
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Xác nhận mật khẩu mới *</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                        </div>
                        <div>
                            <button type="submit" style="background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-key"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

