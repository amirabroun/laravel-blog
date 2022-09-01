<?php

namespace Database\Seeders;

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
        $this->users()->categories()->posts();
    }

    private function users()
    {
        User::factory()->create([
            'first_name' => 'Amir',
            'last_name' => 'Abroun',
            'email' => 'abroon234@gmail.com',
            'password' => 12345678,
            'is_admin' => 1,
        ]); // admin user Amir Abroun

        User::factory(rand(4, 10))->create();

        return $this;
    }

    private function categories()
    {
        Category::factory(rand(4, 10))->create();

        Category::factory()->create([
            'title' => 'Exam',
            'description' => 'Exam category. You can see exam dates in this category'
        ]);

        Category::factory()->create([
            'title' => 'Practis',
            'description' => 'Practis category. You can see practis dates and logs in this category'
        ]);

        Category::factory(1)->create([
            'title' => 'Travel',
            'description' => 'Traveling is such a nice experience. 
                Besides earning knowledge about other people and history of other nations; 
                the essense of travel that makes unpredictble days, makes us efficient and capable.'
        ]);

        Category::factory(1)->create([
            'title' => 'SEO commercial',
            'description' => 'A company for improving SEO science.'
        ]);

        return $this;
    }

    private function posts()
    {
        Post::factory(rand(4, 10))->hasAttached(
            Label::factory(6)->create()
        )->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();

        Post::factory(rand(3, 10))->hasAttached(
            Label::factory(rand(3, 9))->create()
        )->sequence(
            fn () => [
                'user_id' => User::all()->random(),
                'category_id' => Category::all()->random(),
            ]
        )->create();

        return $this;
    }
}
