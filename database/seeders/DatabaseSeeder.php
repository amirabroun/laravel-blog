<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\BlogSeeder\AdminSeeder;
use Database\Seeders\BlogSeeder\GithubUsersSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

        $this->call(GithubUsersSeeder::class);

        Artisan::call('app:add-news');

        $this->insertLabels();

        $this->insertCategory();
    }

    private function insertLabels()
    {
        $labels = [
            "Technology",
            "Programming",
            "Artificial Intelligence",
            "Web Development",
            "Cybersecurity",
            "UI/UX Design",
            "Digital Marketing",
            "Data Science",
            "Cloud Computing",
            "Networking",
            "Blockchain",
            "Mobile Applications",
            "Operating Systems",
            "Robotics",
            "Game Development",
            "Algorithms",
            "Machine Learning",
            "Image Processing",
            "Internet of Things",
            "Data Analytics"
        ];

        foreach ($labels as $title) {
            DB::table('labels')->insert([
                'title' => $title,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function insertCategory()
    {
        $categories = [
            "Technology",
            "Health",
            "Education",
            "Finance",
            "Travel",
            "Food",
            "Lifestyle",
            "Entertainment",
            "Sports",
            "Science",
            "Environment",
            "Art",
            "History",
            "Business",
            "Gaming",
            "Automobile",
            "Fashion",
            "Parenting",
            "Real Estate",
            "Politics"
        ];

        foreach ($categories as $title) {
            DB::table('categories')->insert([
                'title' => $title,
                'uuid' => fake()->uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
