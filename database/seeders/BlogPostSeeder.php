<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;

class BlogPostSeeder extends Seeder
{
    public function run()
    {
        // Create 2 blog posts with user_id = 1
        BlogPost::factory()->count(2)->create([
            'user_id' => 1,
        ])->each(function ($post) {
            $categories = Category::inRandomOrder()->take(rand(0, 2))->pluck('id');
            $post->categories()->attach($categories);
        });

        // Create 3 more blog posts with random user_id
        BlogPost::factory()->count(3)->create()->each(function ($post) {
            $categories = Category::inRandomOrder()->take(rand(0, 2))->pluck('id');
            $post->categories()->attach($categories);
        });
    }
}
