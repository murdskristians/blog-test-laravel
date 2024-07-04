<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'user_id' => 1, // Only use the seeded user with ID 1
            'category_id' => Category::all()->random()->id,
        ];
    }
}
