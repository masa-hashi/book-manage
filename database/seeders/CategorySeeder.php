<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => '小説',     'slug' => 'novel',        'description' => '日本・海外の小説'],
            ['name' => 'ビジネス', 'slug' => 'business',     'description' => 'ビジネス・経済・経営書'],
            ['name' => '技術書',   'slug' => 'tech',         'description' => 'プログラミング・IT技術書'],
            ['name' => '自己啓発', 'slug' => 'self-help',    'description' => '自己啓発・心理・哲学'],
            ['name' => '歴史',     'slug' => 'history',      'description' => '歴史・伝記'],
            ['name' => 'サイエンス','slug' => 'science',     'description' => '科学・自然・数学'],
            ['name' => '漫画',     'slug' => 'manga',        'description' => '漫画・グラフィックノベル'],
            ['name' => 'エッセイ', 'slug' => 'essay',        'description' => 'エッセイ・随筆'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
