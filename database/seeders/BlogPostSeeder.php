<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first(); // Ensure you have a user to associate the posts with

        BlogPost::create([
            'title' => 'Sample Blog Post',
            'body' => 'This is a sample blog post.',
            'user_id' => $user->id, // Ensure you have a user to associate the posts with
        ]);
    }
}
