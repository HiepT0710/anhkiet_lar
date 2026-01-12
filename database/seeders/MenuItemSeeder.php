<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        if (MenuItem::count() > 0) {
            return;
        }

        $home = MenuItem::create(['title' => 'Trang chủ', 'url' => '/', 'order' => 0, 'is_active' => true]);
        $category = MenuItem::create(['title' => 'Danh mục', 'url' => '/danh-muc', 'order' => 1, 'is_active' => true]);
        $news = MenuItem::create(['title' => 'Tin tức', 'url' => '/tin-tuc', 'order' => 2, 'is_active' => true]);
        // Children example under Danh mục
        MenuItem::create(['title' => 'Điện thoại', 'url' => '/danh-muc/dien-thoai', 'parent_id' => $category->id, 'order' => 0, 'is_active' => true]);
        MenuItem::create(['title' => 'Laptop', 'url' => '/danh-muc/laptop', 'parent_id' => $category->id, 'order' => 1, 'is_active' => true]);
    }
}


