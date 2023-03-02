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
    }
}
