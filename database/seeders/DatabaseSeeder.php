<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use ReflectionClass, ReflectionProperty;
use App\Models\{Category, Post, User, Label, Skill};

class DatabaseSeeder extends Seeder
{
    private $adminSkill = [
        [
            'title' => 'Laravel',
            'percent' => 75,
        ],
        [
            'title' => 'Php',
            'percent' => 80,
        ],
        [
            'title' => 'Linux',
            'percent' => 30,
        ],
        [
            'title' => 'Mysql',
            'percent' => 60,
        ],
        [
            'title' => 'Scrum',
            'percent' => 50,
        ]
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $reflection = new ReflectionClass($this);

        collect(
            $reflection->getMethods(ReflectionProperty::IS_PRIVATE)
        )->pluck('name')->map(
            fn ($method) => call_user_func([$this, $method])
        );
    }

    private function admin()
    {
        $user = User::factory()
            ->create([
                'first_name' => 'Amir',
                'last_name' => 'Abroun',
                'email' => 'abroon234@gmail.com',
                'password' => 12345678,
                'is_admin' => 1,
            ]);

        collect($this->adminSkill)->map(
            fn ($skills) => Skill::factory()->for($user)->create($skills)
        );

        return $this;
    }

    private function users()
    {
        User::factory(rand(4, 10))
            ->hasSkills(rand(1, 10))
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
