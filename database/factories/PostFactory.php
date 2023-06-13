<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => fake()->uuid(),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->text(rand(8, 15)),
            'body' => fake()->paragraph(rand(10, 20))
        ];
    }

    /**
     * Indicate that the model's username address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        //
    }
}
