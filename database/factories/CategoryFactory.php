<?php

namespace Database\Factories;

use PersianFaker\PersianFaker;
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
            'title' =>  rand(0, 1) ? PersianFaker::get('Job') : PersianFaker::get('City'),
            'description' => PersianFaker::get('Lorem', ['words' => rand(5, 132)])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        //
    }
}
