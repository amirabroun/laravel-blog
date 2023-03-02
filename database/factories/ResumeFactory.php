<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Queue\Events\Looping;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resume>
 */
class ResumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'summary' => fake()->realText(),
            'experiences' => $this->experiences(),
            'skills' => $this->skills(),
        ];
    }

    private function experiences($count = 7)
    {
        $experiences = [];

        foreach (range(1, $count) as $number) {
            $experiences[] = [
                'company' => fake()->company(),
                'position' => fake()->jobTitle(),
                'started_at' => fake()->time(),
                'finished_at' => fake()->time(),
            ];
        }

        return $experiences;
    }

    private function skills($count = 7)
    {
        $skills = [];

        foreach (range(1, $count) as $number) {
            $skills[] = [
                'title' => fake()->jobTitle(),
                'percent' => fake()->numberBetween(0, 100)
            ];
        }

        return $skills;
    }
}
