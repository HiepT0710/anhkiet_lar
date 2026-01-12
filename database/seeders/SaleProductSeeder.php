<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SaleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        
        // Lấy các danh mục
        $apple = Category::where('slug', 'apple')->first();
        $dienThoai = Category::where('slug', 'dien-thoai')->first();
        $tablet = Category::where('slug', 'tablet')->first();
        $laptop = Category::where('slug', 'laptop')->first();
        
        // Danh sách sản phẩm sale với thông tin chi tiết
        $saleProducts = [
            // Đang sale (active)
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'slug' => 'iphone-15-pro-max-256gb',
                'price' => 29990000,
                'sale_price' => 27990000,
                'sale_starts_at' => $now->subDays(2),
                'sale_ends_at' => $now->addDays(5),
                'is_flash_sale' => true,
                'stock' => 50,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'slug' => 'samsung-galaxy-s24-ultra-512gb',
                'price' => 28990000,
                'sale_price' => 26990000,
                'sale_starts_at' => $now->subDays(1),
                'sale_ends_at' => $now->addDays(7),
                'is_flash_sale' => true,
                'stock' => 30,
            ],
            [
                'name' => 'iPad Pro 12.9 inch 256GB',
                'slug' => 'ipad-pro-12-9-inch-256gb',
                'price' => 34990000,
                'sale_price' => 31990000,
                'sale_starts_at' => $now->subDays(3),
                'sale_ends_at' => $now->addDays(4),
                'is_flash_sale' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Xiaomi 14 Ultra 256GB',
                'slug' => 'xiaomi-14-ultra-256gb',
                'price' => 17990000,
                'sale_price' => 15990000,
                'sale_starts_at' => $now->subDays(1),
                'sale_ends_at' => $now->addDays(6),
                'is_flash_sale' => true,
                'stock' => 40,
            ],
            [
                'name' => 'MacBook Pro M3 14 inch 512GB',
                'slug' => 'macbook-pro-m3-14-inch-512gb',
                'price' => 42990000,
                'sale_price' => 39990000,
                'sale_starts_at' => $now->subDays(2),
                'sale_ends_at' => $now->addDays(8),
                'is_flash_sale' => true,
                'stock' => 15,
            ],
            [
                'name' => 'Samsung Galaxy 2024',
                'slug' => 'samsung-galaxy-2024',
                'price' => 22990000,
                'sale_price' => 21990000,
                'sale_starts_at' => $now->subDays(1),
                'sale_ends_at' => $now->addDays(3),
                'is_flash_sale' => true,
                'stock' => 35,
            ],
            [
                'name' => 'OPPO Find X7 Ultra 512GB',
                'slug' => 'oppo-find-x7-ultra-512gb',
                'price' => 19990000,
                'sale_price' => 17990000,
                'sale_starts_at' => $now->subDays(1),
                'sale_ends_at' => $now->addDays(5),
                'is_flash_sale' => true,
                'stock' => 28,
            ],
            [
                'name' => 'iPhone 14 Pro 128GB',
                'slug' => 'iphone-14-pro-128gb',
                'price' => 24990000,
                'sale_price' => 22990000,
                'sale_starts_at' => $now->subDays(2),
                'sale_ends_at' => $now->addDays(4),
                'is_flash_sale' => true,
                'stock' => 45,
            ],
            
            // Sắp sale (upcoming)
            [
                'name' => 'iPhone 16 Pro Max 512GB',
                'slug' => 'iphone-16-pro-max-512gb',
                'price' => 34990000,
                'sale_price' => 31990000,
                'sale_starts_at' => $now->addDays(2),
                'sale_ends_at' => $now->addDays(10),
                'is_flash_sale' => true,
                'stock' => 20,
            ],
            [
                'name' => 'Samsung Galaxy Z Fold 6 512GB',
                'slug' => 'samsung-galaxy-z-fold-6-512gb',
                'price' => 39990000,
                'sale_price' => 36990000,
                'sale_starts_at' => $now->addDays(3),
                'sale_ends_at' => $now->addDays(12),
                'is_flash_sale' => true,
                'stock' => 12,
            ],
            [
                'name' => 'iPad Air M2 256GB',
                'slug' => 'ipad-air-m2-256gb',
                'price' => 19990000,
                'sale_price' => 17990000,
                'sale_starts_at' => $now->addDays(1),
                'sale_ends_at' => $now->addDays(7),
                'is_flash_sale' => true,
                'stock' => 30,
            ],
        ];

        foreach ($saleProducts as $productData) {
            $product = Product::where('slug', $productData['slug'])->first();
            
            if ($product) {
                // Cập nhật sản phẩm hiện có
                $product->update([
                    'price' => $productData['price'],
                    'sale_price' => $productData['sale_price'],
                    'sale_starts_at' => $productData['sale_starts_at'],
                    'sale_ends_at' => $productData['sale_ends_at'],
                    'is_flash_sale' => $productData['is_flash_sale'],
                    'stock' => $productData['stock'] ?? $product->stock,
                ]);
                $this->command->info("✅ Đã cập nhật sale cho: {$product->name}");
            } else {
                // Tạo sản phẩm mới nếu chưa có
                $categoryId = null;
                if (stripos($productData['name'], 'iPhone') !== false || stripos($productData['name'], 'iPad') !== false || stripos($productData['name'], 'MacBook') !== false) {
                    $categoryId = $apple ? $apple->id : ($dienThoai ? $dienThoai->id : null);
                } elseif (stripos($productData['name'], 'iPad') !== false) {
                    $categoryId = $tablet ? $tablet->id : null;
                } elseif (stripos($productData['name'], 'MacBook') !== false || stripos($productData['name'], 'Laptop') !== false) {
                    $categoryId = $laptop ? $laptop->id : null;
                } else {
                    $categoryId = $dienThoai ? $dienThoai->id : null;
                }

                if ($categoryId) {
                    Product::create([
                        'name' => $productData['name'],
                        'slug' => $productData['slug'],
                        'price' => $productData['price'],
                        'sale_price' => $productData['sale_price'],
                        'sale_starts_at' => $productData['sale_starts_at'],
                        'sale_ends_at' => $productData['sale_ends_at'],
                        'is_flash_sale' => $productData['is_flash_sale'],
                        'stock' => $productData['stock'] ?? 50,
                        'category_id' => $categoryId,
                        'description' => $productData['name'] . ' - Sản phẩm đang được giảm giá',
                        'short_description' => 'Sản phẩm hot',
                        'is_active' => true,
                        'featured' => false,
                    ]);
                    $this->command->info("✅ Đã tạo mới sản phẩm sale: {$productData['name']}");
                }
            }
        }

        // Cập nhật thêm một số sản phẩm ngẫu nhiên thành sale
        $randomProducts = Product::where('is_flash_sale', false)
            ->orWhereNull('sale_price')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        foreach ($randomProducts as $product) {
            if ($product->price > 0) {
                $discountPercent = rand(10, 30); // Giảm 10-30%
                $salePrice = round($product->price * (1 - $discountPercent / 100));
                
                $product->update([
                    'sale_price' => $salePrice,
                    'is_flash_sale' => true,
                    'sale_starts_at' => $now->subDays(rand(0, 2)),
                    'sale_ends_at' => $now->addDays(rand(3, 10)),
                ]);
                $this->command->info("✅ Đã set sale cho: {$product->name} (-{$discountPercent}%)");
            }
        }

        $this->command->info('');
        $this->command->info('🎉 Đã hoàn thành! Tổng số sản phẩm sale: ' . Product::where('is_flash_sale', true)->count());
    }
}
