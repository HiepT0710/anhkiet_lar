<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AnhKiet Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #2c3e50; font-size: 28px; }
        .login-header p { color: #7f8c8d; margin-top: 10px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px 15px; border: 2px solid #ecf0f1; border-radius: 5px; font-size: 16px; }
        .form-group input:focus { outline: none; border-color: #3498db; }
        .btn-login { width: 100%; padding: 15px; background: #3498db; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-login:hover { background: #2980b9; }
        .alert { padding: 12px; border-radius: 5px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-shield-alt"></i> Admin Login</h1>
            <p>AnhKiet Store</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" required autofocus>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>

        <div style="margin-top: 20px; text-align: center; color: #7f8c8d;">
            <p>Tài khoản: admin@anhkiet.store</p>
            <p>Mật khẩu: admin123</p>
        </div>
    </div>
</body>
</html>

