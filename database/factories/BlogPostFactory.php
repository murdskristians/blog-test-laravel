<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (BlogPost $post) {
            // Attach random categories to the post
            $categories = Category::pluck('id')->toArray();
            $post->categories()->attach(Arr::random($categories, mt_rand(0, 2)));
        });
    }
}
