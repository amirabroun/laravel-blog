<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_number' => fake()->unique()->numberBetween(1111111111, 9999999999),
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password'),
        ];
        // example: 
        // User::factory(10)->has(Post::factory()->count(2))->create();
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
