<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use PersianFaker\PersianFaker;

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
        $title[] = PersianFaker::get('Lorem', ['words' => 3]);
        $title[] = PersianFaker::get('Job');
        $title[] = rand(3, 8) . ' ' . PersianFaker::get('City');

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title[array_rand($title)],
            'body' => PersianFaker::get('Lorem', ['words' => rand(5, 132)])
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
