<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Banner Chính',
            'description' => 'Banner quảng cáo sản phẩm mới',
            'image' => 'images/banner/banner1.webp',
            'link' => '#',
            'position' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Khuyến mãi đặc biệt',
            'description' => 'Giảm giá đến 50%',
            'image' => 'images/banner/banner1.webp',
            'link' => '#',
            'position' => 2,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Sản phẩm mới',
            'description' => 'Tìm hiểu ngay các sản phẩm mới nhất',
            'image' => 'images/banner/banner1.webp',
            'link' => '#',
            'position' => 3,
            'is_active' => true,
        ]);
    }
}
