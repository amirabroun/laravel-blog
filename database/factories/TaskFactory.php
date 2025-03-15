<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $time = now()->addDays(rand(1, 10));

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start' => $time,
            'end' => $time->addHour(rand(0, 1)),
            'user_id' => null,
        ];
    }
}
