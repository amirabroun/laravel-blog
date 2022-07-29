<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Category, Post, User, Label};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'email' => 'abroon234@gmail.com',
            'password' => 12345678,
            'is_admin' => 1,
        ]); // admin user Amir Abroun

        User::factory(rand(4, 20))->create();
        Category::factory(rand(4, 20))->create();

        // create some post with label
        Post::factory(rand(4, 100))->hasAttached(
            Label::factory(6)->create()
        )->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();

        Post::factory(rand(3, 20))->hasAttached(
            Label::factory(rand(3, 9))->create()
        )->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();
    }
}
