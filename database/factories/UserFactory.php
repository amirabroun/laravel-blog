<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'uuid' => fake()->uuid(),
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'password' => fake()->password(),
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
