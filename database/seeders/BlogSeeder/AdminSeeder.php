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
                'username' => 'amirabroun',
                'password' => 12345678,
                'is_admin' => 1,
            ]);

        $user->addMediaFromUrl($this->avatarUrl())->usingFileName(
            fake()->uuid() . '.png'
        )->toMediaCollection('avatar');

        Resume::factory()->for($user)->create([
            'summary' => $this->summary(),
            'experiences' => $this->experiences(),
            'education' => $this->education(),
            'skills' => $this->skills(),
            'contact' => $this->contact(),
        ]);
    }

    private function avatarUrl()
    {
        return "https://github.com/amirabroun.png";
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
                'description' => 'The first string generated will always start with the traditional "Lorem ipsum dolor sit amet, consectetur adipiscing elit". Subsequent strings may contain those words but will not explicitly start with them.',
                'started_at' => '22-10-5',
                'finished_at' => '23-10-3',
            ],
            [
                'company' => 'Sanjeman',
                'position' => 'Team lead',
                'description' => null,
                'started_at' => '22-10-5',
                'finished_at' => null,
            ],
        ];
    }

    private function education()
    {
        return [
            [
                'field' => 'IT',
                'university' => 'Hakim Sabzevari',
                'location' => 'Sabzevar',
                'started_at' => '22-10-5',
                'finished_at' => '23-10-3',
            ],
            [
                'field' => 'SoftWare Engineer',
                'university' => 'Sharif',
                'location' => 'Tehran',
                'started_at' => '22-10-5',
                'finished_at' => null,
            ],
        ];
    }

    private function contact()
    {
        return [
            [
                'title' => 'email',
                'link' => 'abroon234@gmail.com',
            ],
            [
                'title' => 'github',
                'link' => 'https://github.com/amirabroun',
            ],
            [
                'title' => 'linkedin',
                'link' => 'https://linkedin.com/in/amir-abroun-4a7ab321a',
            ],
            [
                'title' => 'phone',
                'link' => '09398720306',
            ],
            [
                'title' => 'address',
                'link' => 'Razavi Khorasan, Sabzevar.',
            ]
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
