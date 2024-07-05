<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\BlogPost;

class CommentSeeder extends Seeder
{
    public function run()
    {
        // Create 5 comments with user_id = 1
        BlogPost::all()->random(5)->each(function ($post) {
            Comment::factory()->create([
                'blog_post_id' => $post->id,
                'user_id' => 1,
            ]);
        });

        // Create 3 comments for each blog post with random user_id
        BlogPost::all()->each(function ($post) {
            Comment::factory()->count(3)->create([
                'blog_post_id' => $post->id,
            ]);
        });
    }
}
