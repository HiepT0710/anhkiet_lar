<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class SePayDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo order
        $orderCode = 'AK' . now()->format('ymdHis') . Str::padLeft((string) random_int(0, 999), 3, '0');

        $order = Order::create([
            'user_id' => null,
            'code' => $orderCode,
            'customer_name' => 'Khách demo',
            'customer_phone' => '0900000000',
            'customer_email' => null,
            'customer_address' => 'Hanoi, Vietnam',
            'note' => 'Đơn hàng demo SePay',
            'subtotal_amount' => 100000,
            'discount_amount' => 0,
            'total_amount' => 100000,
            'coupon_id' => null,
            'payment_method' => 'sepay_qr',
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        // Create one demo order item
        // Ensure a Category exists (some installations require non-null category_id)
        $categoryModel = \App\Models\Category::class;
        if (class_exists($categoryModel)) {
            $category = $categoryModel::firstOrCreate(
                ['slug' => 'demo'],
                ['name' => 'Demo', 'description' => 'Danh mục demo']
            );
            $categoryId = $category->id;
        } else {
            $categoryId = null;
        }

        // Create a demo product to satisfy foreign key constraint
        $productData = [
            'name' => 'Sản phẩm demo',
            'slug' => 'san-pham-demo-' . Str::random(6),
            'description' => 'Sản phẩm demo cho SePay',
            'short_description' => 'Demo',
            'price' => 100000,
            'sale_price' => null,
            'stock' => 100,
            'is_active' => true,
        ];
        // If category_id is required, add it
        if ($categoryId !== null) {
            $productData['category_id'] = $categoryId;
        }

        $product = \App\Models\Product::create($productData);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'selected_color' => null,
            'selected_size' => null,
            'quantity' => 1,
            'unit_price' => 100000,
            'total_price' => 100000,
        ]);
    }
}


