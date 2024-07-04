<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\Comment;

class BlogPostSeeder extends Seeder
{
    public function run()
    {
        BlogPost::factory()
            ->count(5)
            ->has(
                Comment::factory()
                    ->count(3)
                    ->state(function (array $attributes, BlogPost $blogPost) {
                        return ['user_id' => 1, 'post_id' => $blogPost->id];
                    }),
                'comments'
            )
            ->create(['user_id' => 1]);
    }
}
