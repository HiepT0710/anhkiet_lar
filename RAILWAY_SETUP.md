# Railway Deployment Setup

## ⚠️ QUAN TRỌNG: Cấu hình Railway

### 1. Root Directory
- Vào Railway Dashboard → Project → Settings
- Tìm mục **"Root Directory"**
- Để **TRỐNG** hoặc nhập `.` (KHÔNG được để `/laravel`)
- Nhấn **Save**

### 2. Environment Variables
Vào tab **Variables** và thêm các biến sau:

```
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app
APP_KEY=base64:... (sẽ được generate tự động)
```

### 3. Database (nếu cần)
Nếu dùng database, thêm:
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

### 4. Start Command
Trong Settings → Deploy → Start Command:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

### 5. Sau khi deploy
Railway sẽ tự động:
- Generate APP_KEY
- Chạy migrations (nếu có)
- Tạo storage link
- Cache config và routes
