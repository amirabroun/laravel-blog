<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\BlogSeeder\AdminSeeder;
use Database\Seeders\BlogSeeder\GithubUsersSeeder;
use Illuminate\Support\Facades\Artisan;

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
    }
}
