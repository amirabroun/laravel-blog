<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CategoryFactory extends Factory
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
            'title' => fake()->unique()->text(rand(8, 15)),
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
