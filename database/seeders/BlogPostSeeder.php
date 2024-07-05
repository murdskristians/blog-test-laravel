<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Category;

class BlogPostSeeder extends Seeder
{
    public function run()
    {
        BlogPost::factory()->count(5)->create()->each(function ($post) {
            $categories = Category::inRandomOrder()->take(rand(0, 2))->pluck('id');
            $post->categories()->attach($categories);
        });
    }
}
