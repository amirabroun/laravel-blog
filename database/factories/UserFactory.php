<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $date = now()->subDays(rand(1, 10));

        return [
            'uuid' => fake()->uuid(),
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'username' => fake()->userName(),
            'password' => 12345678,
            'created_at' => $date,
            'updated_at' => $date,
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
