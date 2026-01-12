<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $category = PostCategory::first();
        if (!$category) {
            $category = PostCategory::create(['name' => 'Tin công nghệ', 'slug' => 'tin-cong-nghe']);
        }

        $samples = [
            [
                'title' => 'Ra mắt sản phẩm mới - Trải nghiệm đỉnh cao',
                'excerpt' => 'Những điểm nhấn nổi bật trên sản phẩm thế hệ mới.',
            ],
            [
                'title' => 'Mẹo tối ưu pin cho smartphone của bạn',
                'excerpt' => 'Các cách đơn giản giúp kéo dài thời lượng pin.',
            ],
            [
                'title' => 'So sánh camera các flagship 2025',
                'excerpt' => 'Chất lượng ảnh chụp và video trong nhiều điều kiện.',
            ],
        ];

        foreach ($samples as $s) {
            $slug = Str::slug($s['title']);
            Post::firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $s['title'],
                    'excerpt' => $s['excerpt'],
                    'content' => '<p>'.e($s['excerpt']).'</p>',
                    'post_category_id' => $category->id,
                    'is_active' => true,
                    'published_at' => now(),
                ]
            );
        }
    }
}


