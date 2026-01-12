<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai', 'order' => 1, 'description' => 'Điện thoại thông minh', 'icon' => 'fas fa-mobile-alt'],
            ['name' => 'Tablet', 'slug' => 'tablet', 'order' => 2, 'description' => 'Máy tính bảng', 'icon' => 'fas fa-tablet-alt'],
            ['name' => 'Laptop', 'slug' => 'laptop', 'order' => 3, 'description' => 'Máy tính xách tay', 'icon' => 'fas fa-laptop'],
            ['name' => 'Tai nghe', 'slug' => 'tai-nghe', 'order' => 4, 'description' => 'Tai nghe & phụ kiện âm thanh', 'icon' => 'fas fa-headphones'],
            ['name' => 'Sạc & Pin', 'slug' => 'sac-pin', 'order' => 5, 'description' => 'Sạc dự phòng & Pin', 'icon' => 'fas fa-battery-full'],
            ['name' => 'Thẻ nhớ', 'slug' => 'the-nho', 'order' => 6, 'description' => 'Thẻ nhớ & USB', 'icon' => 'fas fa-memory'],
            ['name' => 'Ốp lưng', 'slug' => 'op-lung', 'order' => 7, 'description' => 'Ốp lưng điện thoại', 'icon' => 'fas fa-shield-alt'],
            ['name' => 'Apple', 'slug' => 'apple', 'order' => 8, 'description' => 'iPhone & iPad', 'icon' => 'fab fa-apple'],
            ['name' => 'Máy cũ', 'slug' => 'may-cu', 'order' => 9, 'description' => 'Điện thoại đã qua sử dụng', 'icon' => 'fas fa-mobile-screen-button'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
