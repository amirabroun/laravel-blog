<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use ReflectionClass, ReflectionProperty;
use Database\Seeders\BlogSeeder\AdminSeeder;
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
        $this->call(AdminSeeder::class);

        $reflection = new ReflectionClass($this);

        collect(
            $reflection->getMethods(ReflectionProperty::IS_PRIVATE)
        )->pluck('name')->map(
            fn ($method) => call_user_func([$this, $method])
        );
    }

    private function users()
    {
        User::factory(rand(4, 10))
            ->create();

        return $this;
    }

    private function categories()
    {
        Category::factory(rand(4, 10))->create();

        Category::factory()->create([
            'title' => 'Exam',
        ]);

        Category::factory()->create([
            'title' => 'Practis',
        ]);

        Category::factory(1)->create([
            'title' => 'Travel',
        ]);

        Category::factory(1)->create([
            'title' => 'SEO commercial',
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
