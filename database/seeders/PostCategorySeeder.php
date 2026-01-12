<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tin công nghệ', 'slug' => 'tin-cong-nghe'],
            ['name' => 'Kinh nghiệm hay', 'slug' => 'kinh-nghiem-hay'],
            ['name' => 'Khuyến mãi', 'slug' => 'khuyen-mai'],
        ];

        foreach ($categories as $c) {
            PostCategory::firstOrCreate(['slug' => $c['slug']], $c + ['is_active' => true]);
        }
    }
}


