<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Auth Routes
Route::get('/dang-nhap', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.show');
Route::post('/dang-nhap', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/dang-ky', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register.show');
Route::post('/dang-ky', [App\Http\Controllers\AuthController::class, 'register'])->name('register');

// Social login
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->whereIn('provider', ['google', 'facebook', 'zalo'])
    ->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->whereIn('provider', ['google', 'facebook', 'zalo'])
    ->name('social.callback');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tim-kiem', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::get('/tim-kiem/goi-y', [App\Http\Controllers\HomeController::class, 'searchSuggestions'])->name('search.suggestions');
Route::get('/khuyen-mai', [App\Http\Controllers\SaleController::class, 'index'])->name('sale.index');
Route::get('/san-pham/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::post('/danh-gia', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/danh-muc', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
Route::get('/danh-muc/{slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
Route::get('/trang/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');
// Blog Routes
Route::get('/tin-tuc', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('/tin-tuc/chuyen-muc/{slug}', [App\Http\Controllers\PostController::class, 'category'])->name('posts.category');
Route::get('/tin-tuc/{slug}', [App\Http\Controllers\PostController::class, 'show'])->name('posts.show');

// Chat Routes - public
Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/faq', [App\Http\Controllers\ChatController::class, 'getFaq'])->name('chat.faq');

// Wishlist Routes - yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/yeu-thich', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/yeu-thich/{product}', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
// Cart + Checkout Routes - yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    // Account Routes
    Route::get('/tai-khoan', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
    Route::get('/tai-khoan/thong-tin', [App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');
    Route::post('/tai-khoan/thong-tin', [App\Http\Controllers\AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/tai-khoan/doi-mat-khau', [App\Http\Controllers\AccountController::class, 'changePassword'])->name('account.password.update');
    Route::get('/tai-khoan/don-hang', [App\Http\Controllers\AccountController::class, 'orders'])->name('account.orders');
    Route::get('/tai-khoan/don-hang/{order}', [App\Http\Controllers\AccountController::class, 'orderDetail'])->name('account.order-detail');
    Route::get('/tai-khoan/danh-gia', [App\Http\Controllers\AccountController::class, 'reviews'])->name('account.reviews');

    Route::get('/gio-hang', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/gio-hang/them', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/gio-hang/cap-nhat', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/gio-hang/xoa', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    Route::get('/thanh-toan', [App\Http\Controllers\PaymentController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/thanh-toan/submit', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('checkout.vnpay');
    Route::post('/thanh-toan/apply-coupon', [App\Http\Controllers\PaymentController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::get('/thanh-toan/qr/{code}', [App\Http\Controllers\PaymentController::class, 'showQr'])->name('payment.qr');
    // SePay return/webhook endpoints
    Route::get('/thanh-toan/sepay/return', [App\Http\Controllers\PaymentController::class, 'handleSePayReturn'])->name('payment.sepay.return');
    Route::post('/thanh-toan/sepay/webhook', [App\Http\Controllers\PaymentController::class, 'handleSePayWebhook'])->name('payment.sepay.webhook');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Product Routes
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/{product}/toggle', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle');
    Route::get('products/inventory/index', [App\Http\Controllers\Admin\ProductController::class, 'inventory'])->name('products.inventory');
    Route::post('products/{product}/update-stock', [App\Http\Controllers\Admin\ProductController::class, 'updateStock'])->name('products.update-stock');
    
    // Sale Products Routes
    Route::get('sale-products', [App\Http\Controllers\Admin\SaleProductController::class, 'index'])->name('sale-products.index');
    Route::post('sale-products/bulk-set-sale', [App\Http\Controllers\Admin\SaleProductController::class, 'bulkSetSale'])->name('sale-products.bulk-set-sale');
    Route::post('sale-products/{product}/remove-sale', [App\Http\Controllers\Admin\SaleProductController::class, 'removeSale'])->name('sale-products.remove-sale');
    
    // Category Routes  
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/{category}/toggle', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    
    // Banner Routes
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
    Route::post('banners/{banner}/toggle', [App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.toggle');

    // Brand Routes
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);
    Route::post('brands/{brand}/toggle', [App\Http\Controllers\Admin\BrandController::class, 'toggleStatus'])->name('brands.toggle');

    // Post Categories
    Route::resource('post-categories', App\Http\Controllers\Admin\PostCategoryController::class);
    Route::post('post-categories/{post_category}/toggle', [App\Http\Controllers\Admin\PostCategoryController::class, 'toggleStatus'])->name('post-categories.toggle');

    // Posts
    Route::get('posts-admin', [App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.admin.index');
    // Avoid naming collision with public posts.show — map admin show to a different name
    Route::resource('posts', App\Http\Controllers\Admin\PostController::class)->except(['index'])->names(['show' => 'posts.admin.show']);
    Route::post('posts/{post}/toggle', [App\Http\Controllers\Admin\PostController::class, 'toggleStatus'])->name('posts.toggle');

    // Coupons
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    Route::post('coupons/{coupon}/toggle', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.toggle');


    // Menus
    Route::resource('menus', App\Http\Controllers\Admin\MenuItemController::class);
    Route::post('menus/{menu}/toggle', [App\Http\Controllers\Admin\MenuItemController::class, 'toggleStatus'])->name('menus.toggle');

    // Messages/Chat Routes
    Route::get('messages', [App\Http\Controllers\ChatController::class, 'index'])->name('admin.messages.index');
    Route::get('messages/{message}', [App\Http\Controllers\ChatController::class, 'show'])->name('admin.messages.show');
    Route::post('messages/{message}/reply', [App\Http\Controllers\ChatController::class, 'reply'])->name('admin.messages.reply');
    Route::post('messages/{message}/read', [App\Http\Controllers\ChatController::class, 'markAsRead'])->name('admin.messages.read');

    // Auto Reply Routes
    Route::resource('auto-replies', App\Http\Controllers\Admin\AutoReplyController::class)->names('admin.auto-replies');
    Route::post('auto-replies/{autoReply}/toggle', [App\Http\Controllers\Admin\AutoReplyController::class, 'toggleStatus'])->name('admin.auto-replies.toggle');

    // AI Settings Routes
    Route::get('ai-settings', [App\Http\Controllers\Admin\AISettingsController::class, 'index'])->name('admin.ai-settings.index');
    Route::put('ai-settings', [App\Http\Controllers\Admin\AISettingsController::class, 'update'])->name('admin.ai-settings.update');
    Route::post('ai-settings/test-connection', [App\Http\Controllers\Admin\AISettingsController::class, 'testConnection'])->name('admin.ai-settings.test-connection');
    Route::post('ai-settings/test', [App\Http\Controllers\Admin\AISettingsController::class, 'testAI'])->name('admin.ai-settings.test');

    // Review Management Routes
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::post('reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('admin.reviews.reject');
    Route::delete('reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    Route::post('reviews/bulk-approve', [App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('admin.reviews.bulk-approve');
    Route::post('reviews/bulk-delete', [App\Http\Controllers\Admin\ReviewController::class, 'bulkDelete'])->name('admin.reviews.bulk-delete');

    // Order Management Routes
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/statistics', [App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('admin.orders.statistics');
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::patch('orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.payment-status');
});

// Logout
Route::post('/dang-xuat', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
