<?php

namespace Database\Seeders\BlogSeeder;

use Illuminate\Database\Seeder;
use App\Models\{User, Resume};

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()
            ->create([
                'first_name' => 'Amir',
                'last_name' => 'Abroun',
                'email' => 'abroon234@gmail.com',
                'password' => 12345678,
                'is_admin' => 1,
            ]);

        Resume::factory()->for($user)->create([
            'summary' => $this->summary(),
            'experiences' => $this->experiences(),
            'skills' => $this->skills(),
        ]);
    }

    private function summary()
    {
        return 'Experienced Software Developer with over 3 years of experience in Web Development. Results-focused software engineering professional with extensive experience in development and testing. Well-versed in promoting objective-oriented approaches to real-time software development. Analytical and detail-oriented.';
    }

    private function experiences()
    {
        return [
            [
                'company' => 'Sanjeman',
                'position' => 'Backend developer',
                'started_at' => '22-10-5',
                'finished_at' => null,
            ],
        ];
    }

    private function skills()
    {
        return [
            [
                'title' => 'Laravel',
                'percent' => 75,
            ],
            [
                'title' => 'Php',
                'percent' => 95,
            ],
            [
                'title' => 'Object-Oriented',
                'percent' => 90,
            ],
            [
                'title' => 'Linux',
                'percent' => 40,
            ],
            [
                'title' => 'Docker',
                'percent' => 60,
            ],
            [
                'title' => 'Nginx',
                'percent' => 40,
            ],
            [
                'title' => 'Git',
                'percent' => 80,
            ],
            [
                'title' => 'Github',
                'percent' => 20,
            ],
            [
                'title' => 'Bash',
                'percent' => 70,
            ],
            [
                'title' => 'Mysql',
                'percent' => 60,
            ],
            [
                'title' => 'Redis',
                'percent' => 30,
            ],
            [
                'title' => 'Project Management',
                'percent' => 60,
            ],
            [
                'title' => 'JavaScript',
                'percent' => 55,
            ],
            [
                'title' => 'JQuery',
                'percent' => 40,
            ],
            [
                'title' => 'Ajax',
                'percent' => 35,
            ],
            [
                'title' => 'Debuging',
                'percent' => 80,
            ],
            [
                'title' => 'Scrum',
                'percent' => 60,
            ],
            [
                'title' => 'Agile',
                'percent' => 30,
            ],
        ];
    }
}
