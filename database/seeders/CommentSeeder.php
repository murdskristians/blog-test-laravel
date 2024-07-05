<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run()
    {
        BlogPost::all()->each(function ($post) {
            Comment::factory()->count(3)->create([
                'blog_post_id' => $post->id,
                'user_id' => User::inRandomOrder()->first()->id,
            ]);
        });
    }
}
