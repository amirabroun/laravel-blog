<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Category, Post, User};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Category::factory(10)->create();

        Post::factory(10)->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();
    }
}
