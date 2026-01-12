<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    protected function upsertProduct(array $data): void
    {
        Product::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dienThoai = Category::where('slug', 'dien-thoai')->first();
        $apple = Category::where('slug', 'apple')->first();
        $tablet = Category::where('slug', 'tablet')->first();
        $laptop = Category::where('slug', 'laptop')->first();
        $taiNghe = Category::where('slug', 'tai-nghe')->first();
        $sacPin = Category::where('slug', 'sac-pin')->first();
        $opLung = Category::where('slug', 'op-lung')->first();
        $theNho = Category::where('slug', 'the-nho')->first();
        $mayCu = Category::where('slug', 'may-cu')->first();
        
        $products = [
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'slug' => 'iphone-15-pro-max-256gb',
                'price' => 29990000,
                'sale_price' => 27990000,
                'description' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera cải tiến',
                'short_description' => 'iPhone Pro Max mới nhất',
                'category_id' => $apple->id,
                'featured' => true,
                'stock' => 50,
                'images' => json_encode(['products/iphone-15-pro-max.jpg']),
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'slug' => 'samsung-galaxy-s24-ultra-512gb',
                'price' => 28990000,
                'sale_price' => 26990000,
                'description' => 'Galaxy S24 Ultra với bút S-Pen, camera 200MP',
                'short_description' => 'Galaxy S24 Ultra flagship',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 30,
            ],
            [
                'name' => 'Xiaomi 14 Ultra 256GB',
                'slug' => 'xiaomi-14-ultra-256gb',
                'price' => 17990000,
                'sale_price' => 16990000,
                'description' => 'Xiaomi 14 Ultra với camera Leica 50MP',
                'short_description' => 'Flagship Xiaomi',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 40,
            ],
            [
                'name' => 'OPPO Find X7 Ultra 512GB',
                'slug' => 'oppo-find-x7-ultra-512gb',
                'price' => 19990000,
                'sale_price' => 18990000,
                'description' => 'OPPO Find X7 Ultra camera 50MP triple',
                'short_description' => 'OPPO flagship mới',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 25,
            ],
            [
                'name' => 'Vivo X100 Pro 256GB',
                'slug' => 'vivo-x100-pro-256gb',
                'price' => 15990000,
                'sale_price' => 14990000,
                'description' => 'Vivo X100 Pro chip MediaTek Dimensity 9300',
                'short_description' => 'Vivo flagship',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 35,
            ],
            [
                'name' => 'Realme GT 5 Pro 512GB',
                'slug' => 'realme-gt-5-pro-512gb',
                'price' => 11990000,
                'sale_price' => 10990000,
                'description' => 'Realme GT 5 Pro snapdragon 8 gen 3',
                'short_description' => 'Realme flagship',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 45,
            ],
            [
                'name' => 'OnePlus 12 256GB',
                'slug' => 'oneplus-12-256gb',
                'price' => 16990000,
                'sale_price' => 15990000,
                'description' => 'OnePlus 12 flagship mới',
                'short_description' => 'OnePlus 12',
                'category_id' => $dienThoai->id,
                'featured' => true,
                'stock' => 28,
            ],
            [
                'name' => 'iPhone 14 Pro 128GB',
                'slug' => 'iphone-14-pro-128gb',
                'price' => 21990000,
                'sale_price' => 19990000,
                'description' => 'iPhone 14 Pro cũ như mới',
                'short_description' => 'iPhone 14 Pro',
                'category_id' => $apple->id,
                'featured' => false,
                'stock' => 20,
            ],
            [
                'name' => 'Samsung Galaxy S23 Ultra 256GB',
                'slug' => 'samsung-galaxy-s23-ultra-256gb',
                'price' => 20990000,
                'sale_price' => 18990000,
                'description' => 'Samsung Galaxy S23 Ultra',
                'short_description' => 'Galaxy S23 Ultra',
                'category_id' => $dienThoai->id,
                'featured' => false,
                'stock' => 15,
            ],
            [
                'name' => 'Xiaomi 13 Pro 256GB',
                'slug' => 'xiaomi-13-pro-256gb',
                'price' => 13990000,
                'sale_price' => 12990000,
                'description' => 'Xiaomi 13 Pro camera Leica',
                'short_description' => 'Xiaomi 13 Pro',
                'category_id' => $dienThoai->id,
                'featured' => false,
                'stock' => 22,
            ],
            [
                'name' => 'Google Pixel 8 Pro 256GB',
                'slug' => 'google-pixel-8-pro-256gb',
                'price' => 19990000,
                'sale_price' => 18990000,
                'description' => 'Google Pixel 8 Pro camera AI',
                'short_description' => 'Pixel 8 Pro',
                'category_id' => $dienThoai->id,
                'featured' => false,
                'stock' => 18,
            ],
            [
                'name' => 'Nothing Phone 2 256GB',
                'slug' => 'nothing-phone-2-256gb',
                'price' => 11990000,
                'sale_price' => 10990000,
                'description' => 'Nothing Phone 2 thiết kế độc đáo',
                'short_description' => 'Nothing Phone 2',
                'category_id' => $dienThoai->id,
                'featured' => false,
                'stock' => 32,
            ],
        ];

        // Tablet products
        if ($tablet) {
            $this->upsertProduct([
                'name' => 'iPad Pro 12.9 inch 256GB',
                'slug' => 'ipad-pro-12.9-256gb',
                'price' => 34990000,
                'sale_price' => 32990000,
                'description' => 'iPad Pro M3 chip',
                'short_description' => 'iPad Pro M3',
                'category_id' => $tablet->id,
                'featured' => true,
                'stock' => 15,
            ]);
            $this->upsertProduct([
                'name' => 'Samsung Galaxy Tab S9 Ultra 512GB',
                'slug' => 'samsung-tab-s9-ultra-512gb',
                'price' => 28990000,
                'sale_price' => 26990000,
                'description' => 'Galaxy Tab S9 Ultra flagship',
                'short_description' => 'Tab S9 Ultra',
                'category_id' => $tablet->id,
                'featured' => true,
                'stock' => 12,
            ]);
        }

        // Laptop products
        if ($laptop) {
            $this->upsertProduct([
                'name' => 'MacBook Pro 14 M3 Pro 512GB',
                'slug' => 'macbook-pro-14-m3-pro-512gb',
                'price' => 52990000,
                'sale_price' => 49990000,
                'description' => 'MacBook Pro M3 Pro',
                'short_description' => 'MacBook Pro M3',
                'category_id' => $laptop->id,
                'featured' => true,
                'stock' => 8,
            ]);
            $this->upsertProduct([
                'name' => 'Dell XPS 15 9530',
                'slug' => 'dell-xps-15-9530',
                'price' => 39990000,
                'sale_price' => 37990000,
                'description' => 'Dell XPS 15 premium laptop',
                'short_description' => 'Dell XPS 15',
                'category_id' => $laptop->id,
                'featured' => true,
                'stock' => 10,
            ]);
        }

        // Tai nghe products
        if ($taiNghe) {
            $this->upsertProduct([
                'name' => 'AirPods Pro 2',
                'slug' => 'airpods-pro-2',
                'price' => 5990000,
                'sale_price' => 5490000,
                'description' => 'AirPods Pro 2 chống ồn active',
                'short_description' => 'AirPods Pro 2',
                'category_id' => $taiNghe->id,
                'featured' => false,
                'stock' => 50,
            ]);
            $this->upsertProduct([
                'name' => 'Sony WH-1000XM5',
                'slug' => 'sony-wh-1000xm5',
                'price' => 8990000,
                'sale_price' => 8490000,
                'description' => 'Tai nghe chống ồn Sony',
                'short_description' => 'Sony WH-1000XM5',
                'category_id' => $taiNghe->id,
                'featured' => false,
                'stock' => 25,
            ]);
        }

        // Sạc pin products
        if ($sacPin) {
            $this->upsertProduct([
                'name' => 'Sạc nhanh 100W',
                'slug' => 'sac-nhanh-100w',
                'price' => 799000,
                'sale_price' => 699000,
                'description' => 'Sạc nhanh 100W',
                'short_description' => 'Sạc 100W',
                'category_id' => $sacPin->id,
                'featured' => false,
                'stock' => 100,
            ]);
        }

        // Thẻ nhớ products
        if ($theNho) {
            $this->upsertProduct([
                'name' => 'Thẻ nhớ MicroSD 256GB Class 10',
                'slug' => 'the-nho-microsd-256gb',
                'price' => 599000,
                'sale_price' => 549000,
                'description' => 'Thẻ nhớ 256GB tốc độ cao',
                'short_description' => 'MicroSD 256GB',
                'category_id' => $theNho->id,
                'featured' => false,
                'stock' => 200,
            ]);
        }

        // Ốp lưng products
        if ($opLung) {
            $this->upsertProduct([
                'name' => 'Ốp lưng iPhone 15 Pro Max',
                'slug' => 'op-lung-iphone-15-pro-max',
                'price' => 299000,
                'sale_price' => 249000,
                'description' => 'Ốp lưng trong suốt bảo vệ',
                'short_description' => 'Ốp lưng iPhone',
                'category_id' => $opLung->id,
                'featured' => false,
                'stock' => 500,
            ]);
        }

        // Máy cũ products
        if ($mayCu) {
            $this->upsertProduct([
                'name' => 'iPhone 12 Pro Max 128GB (99%)',
                'slug' => 'iphone-12-pro-max-128gb-cu',
                'price' => 15990000,
                'sale_price' => 14990000,
                'description' => 'iPhone 12 Pro Max cũ 99%',
                'short_description' => 'iPhone 12 cũ',
                'category_id' => $mayCu->id,
                'featured' => false,
                'stock' => 5,
            ]);
        }

        foreach ($products as $product) {
            $this->upsertProduct($product);
        }

        // Tạo thêm sản phẩm demo cho mỗi danh mục để làm đầy giao diện
        $faker = \Faker\Factory::create('vi_VN');

        $extraCategories = [
            $dienThoai,
            $tablet,
            $laptop,
            $taiNghe,
            $sacPin,
            $opLung,
            $theNho,
            $mayCu,
        ];

        foreach ($extraCategories as $category) {
            if (!$category) {
                continue;
            }

            // Cấu hình tên & giá hợp lý theo từng danh mục
            $slug = $category->slug;

            switch ($slug) {
                case 'dien-thoai':
                case 'apple':
                case 'may-cu':
                    $baseNames = ['iPhone', 'Samsung Galaxy', 'Xiaomi', 'OPPO', 'Vivo', 'Realme', 'OnePlus'];
                    $minPrice = 3000000;
                    $maxPrice = 45000000;
                    break;
                case 'tablet':
                    $baseNames = ['iPad', 'Galaxy Tab', 'Xiaomi Pad'];
                    $minPrice = 4000000;
                    $maxPrice = 30000000;
                    break;
                case 'laptop':
                    $baseNames = [
                        'Laptop ASUS TUF Gaming F15 i5 16GB 512GB RTX 3050 15.6\" FHD',
                        'Laptop Acer Aspire 7 i5 8GB 512GB RTX 2050 15.6\" FHD',
                        'Laptop Lenovo IdeaPad Slim 3 R5 16GB 512GB 14\" FHD',
                        'Laptop HP 15s i3 8GB 256GB 15.6\" FHD',
                        'Laptop MacBook Air M2 8GB 256GB 13.6\"',
                        'Laptop MacBook Pro 14 M3 Pro 16GB 512GB',
                    ];
                    $minPrice = 8000000;
                    $maxPrice = 60000000;
                    break;
                case 'tai-nghe':
                    $baseNames = ['Tai nghe Bluetooth', 'Tai nghe chụp tai', 'Tai nghe gaming', 'Tai nghe true wireless'];
                    $minPrice = 300000;
                    $maxPrice = 9000000;
                    break;
                case 'sac-pin':
                    $baseNames = ['Sạc nhanh', 'Pin sạc dự phòng', 'Cốc sạc'];
                    $minPrice = 150000;
                    $maxPrice = 1500000;
                    break;
                case 'the-nho':
                    $baseNames = ['Thẻ nhớ MicroSD', 'Thẻ nhớ SD'];
                    $minPrice = 100000;
                    $maxPrice = 1200000;
                    break;
                case 'op-lung':
                    $baseNames = ['Ốp lưng trong suốt', 'Ốp lưng chống sốc', 'Ốp lưng da'];
                    $minPrice = 60000;
                    $maxPrice = 600000;
                    break;
                default:
                    $baseNames = [$category->name . ' cao cấp', $category->name . ' giá rẻ'];
                    $minPrice = 200000;
                    $maxPrice = 10000000;
                    break;
            }

            // Đảm bảo mỗi danh mục có khoảng 20 sản phẩm
            $currentCount = Product::where('category_id', $category->id)->count();
            $need = max(0, 20 - $currentCount);

            for ($i = 1; $i <= $need; $i++) {
                $base = $faker->randomElement($baseNames);
                $variant = $faker->randomElement(['', ' Pro', ' Plus', ' Max', ' 5G', ' 2024']);
                $name = trim($base . $variant);

                // Tạo slug đẹp và đảm bảo không trùng
                $slugBase = Str::slug($name);
                $slug = $slugBase;
                $k = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $slugBase . '-' . $k;
                    $k++;
                }

                // Giá hợp lý, làm tròn đến 1.000đ
                $price = $faker->numberBetween($minPrice, $maxPrice);
                $price = (int) (round($price / 1000) * 1000);

                $salePrice = null;
                if ($faker->boolean(50)) {
                    $discountPercent = $faker->numberBetween(5, 25); // giảm 5–25%
                    $salePrice = (int) (round(($price * (100 - $discountPercent) / 100) / 1000) * 1000);
                }

                // Mô tả thân thiện theo từng danh mục
                switch ($slug) {
                    case 'dien-thoai':
                    case 'apple':
                    case 'may-cu':
                        $shortDesc = "Điện thoại {$name} với hiệu năng ổn định, phù hợp nhu cầu học tập, làm việc và giải trí hằng ngày.";
                        $longDesc = "{$name} sở hữu thiết kế hiện đại, màn hình sắc nét và thời lượng pin bền bỉ. Máy đáp ứng tốt các nhu cầu phổ biến từ lướt web, mạng xã hội cho tới giải trí đa phương tiện. Sản phẩm được phân phối chính hãng tại AnhKiet Store, hỗ trợ bảo hành đầy đủ và nhiều ưu đãi hấp dẫn khi mua online.";
                        break;
                    case 'tablet':
                        $shortDesc = "Máy tính bảng {$name} phục vụ tốt cho nhu cầu giải trí và làm việc cơ bản.";
                        $longDesc = "{$name} có màn hình lớn, thích hợp xem phim, lướt web và học tập trực tuyến. Thiết kế mỏng nhẹ giúp bạn dễ dàng mang theo bên mình. Khi mua tại AnhKiet Store, khách hàng được hỗ trợ cài đặt ban đầu và tư vấn phụ kiện đi kèm.";
                        break;
                    case 'laptop':
                        $shortDesc = "Laptop {$name} cân bằng giữa hiệu năng và tính di động, phù hợp học tập và làm việc văn phòng.";
                        $longDesc = "{$name} được trang bị cấu hình đủ mạnh cho các tác vụ văn phòng, học online và giải trí nhẹ nhàng. Thiết kế hiện đại, bàn phím dễ gõ và thời lượng pin ổn giúp bạn yên tâm sử dụng cả ngày dài. Sản phẩm chính hãng, được AnhKiet Store hỗ trợ bảo hành và nâng cấp khi cần.";
                        break;
                    case 'tai-nghe':
                        $shortDesc = "Tai nghe {$name} mang lại âm thanh rõ ràng, phù hợp nghe nhạc và xem phim mỗi ngày.";
                        $longDesc = "{$name} có thiết kế gọn nhẹ, đeo thoải mái trong thời gian dài. Chất âm cân bằng, dễ nghe cùng khả năng kết nối ổn định với điện thoại, tablet và laptop. Khi mua tại AnhKiet Store, bạn nhận được chính sách đổi trả và bảo hành theo quy định.";
                        break;
                    case 'sac-pin':
                        $shortDesc = "Phụ kiện {$name} giúp sạc thiết bị nhanh chóng và an toàn.";
                        $longDesc = "{$name} hỗ trợ nhiều dòng điện thoại, máy tính bảng phổ biến trên thị trường. Thiết kế nhỏ gọn, dễ mang theo, chất liệu chắc chắn giúp tăng độ bền trong quá trình sử dụng. Sản phẩm được phân phối bởi AnhKiet Store với chế độ bảo hành rõ ràng.";
                        break;
                    case 'the-nho':
                        $shortDesc = "Thẻ nhớ {$name} mở rộng dung lượng lưu trữ cho điện thoại, máy ảnh và nhiều thiết bị khác.";
                        $longDesc = "{$name} có tốc độ đọc ghi ổn định, phù hợp lưu trữ hình ảnh, video và tài liệu quan trọng. Thiết kế bền bỉ, chịu được nhiều điều kiện sử dụng khác nhau. Khi mua tại AnhKiet Store, bạn nhận được sản phẩm chính hãng kèm chính sách bảo hành.";
                        break;
                    case 'op-lung':
                        $shortDesc = "Ốp lưng {$name} bảo vệ điện thoại khỏi trầy xước và va đập nhẹ.";
                        $longDesc = "{$name} được làm từ chất liệu bền, ôm khít thân máy, giúp cầm nắm chắc tay hơn. Thiết kế đơn giản, dễ phối với nhiều phong cách khác nhau. Sản phẩm có sẵn tại AnhKiet Store với nhiều mẫu mã để bạn lựa chọn.";
                        break;
                    default:
                        $shortDesc = "{$name} là sản phẩm thuộc danh mục {$category->name}, phù hợp nhiều nhu cầu sử dụng khác nhau.";
                        $longDesc = "{$name} được phân phối chính hãng tại AnhKiet Store, đảm bảo nguồn gốc rõ ràng và chế độ bảo hành minh bạch. Bạn có thể lựa chọn thêm nhiều phụ kiện đi kèm để tối ưu trải nghiệm sử dụng.";
                        break;
                }

                Product::create([
                    'name' => $name,
                    'slug' => $slug,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'description' => $longDesc,
                    'short_description' => $shortDesc,
                    'category_id' => $category->id,
                    'featured' => $faker->boolean(20),
                    'stock' => $faker->numberBetween(5, 150),
                    'images' => null,
                ]);
            }
        }

        // Gán ảnh theo tên sản phẩm từ thư mục storage/app/public/products
        $files = collect(Storage::disk('public')->files('products'))
            ->filter(function ($path) {
                return preg_match('/\.(jpe?g|png|webp|gif)$/i', $path);
            })
            ->values();

        if ($files->isNotEmpty()) {
            $getImageForName = function (string $productName) use ($files) {
                $nameSlug = Str::slug($productName);

                // Chỉ gán nếu tìm được file có tên gần giống slug của sản phẩm
                $matched = $files->first(function ($path) use ($nameSlug) {
                    $fileSlug = Str::slug(pathinfo($path, PATHINFO_FILENAME));
                    return Str::contains($fileSlug, $nameSlug)
                        || Str::contains($nameSlug, $fileSlug);
                });

                return $matched ?: null;
            };

            Product::whereNull('images')->orWhere('images', '[]')->get()->each(function (Product $product) use ($getImageForName) {
                $imagePath = $getImageForName($product->name);
                if ($imagePath) {
                    $product->images = json_encode([$imagePath]);
                    $product->save();
                }
            });
        }
    }
}
