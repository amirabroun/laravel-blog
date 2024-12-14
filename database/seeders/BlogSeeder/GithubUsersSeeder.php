<?php

namespace Database\Seeders\BlogSeeder;

use Illuminate\Database\Seeder;
use App\Models\User;
use GrahamCampbell\GitHub\Facades\GitHub;

class GithubUsersSeeder extends Seeder
{
    private string $adminGithubUserName = 'amirabroun';

    public function run()
    {
        $adminUser = User::query()->where('username', $this->adminGithubUserName)->first();

        $this->seedFollowersAndFollowingRecursively($adminUser, $this->adminGithubUserName);
    }

    private function seedFollowersAndFollowingRecursively($user, $githubUsername, $depth = 1)
    {
        if ($depth > 1) {
            return; // Limit recursion depth to avoid rate-limiting issues
        }

        $count = $depth == 1 ? null : rand(1, 4);

        foreach ($this->getFollowersList($githubUsername, $count) as $githubUser) {
            $newUser = $this->getOrCreateUser($githubUser);

            $newUser->follow($user);

            if ($githubUser['avatar_url']) {
                $newUser->addMediaFromUrl($githubUser['avatar_url'])
                    ->usingFileName(fake()->uuid() . '.png')
                    ->toMediaCollection('avatar');
            }

            $this->seedFollowersAndFollowingRecursively($newUser, $githubUser['username'], $depth + 1);
        }

        foreach ($this->getFollowingList($githubUsername, $count) as $githubUser) {
            $newUser = $this->getOrCreateUser($githubUser);

            $user->follow($newUser);

            if ($githubUser['avatar_url']) {
                $newUser->addMediaFromUrl($githubUser['avatar_url'])
                    ->usingFileName(fake()->uuid() . '.png')
                    ->toMediaCollection('avatar');
            }

            $this->seedFollowersAndFollowingRecursively($newUser, $githubUser['username'], $depth + 1);
        }
    }

    private function getOrCreateUser($githubUser)
    {
        $user = User::query()->where('username', $githubUser['username'])->first();

        return $user ?? User::factory()->create([
            'first_name' => $githubUser['first_name'],
            'last_name' => $githubUser['last_name'],
            'username' => $githubUser['username'],
            'password' => '12345678',
            'is_admin' => 0,
        ]);
    }

    private function getFollowersList($username, $count = null)
    {
        $followers = GitHub::user()->followers($username);

        $followers = $count ? array_slice($followers, 0, $count) : $followers;
        $githubUsers = [];

        foreach ($followers as $follower) {
            $githubUsers[] = GitHub::user()->showById($follower['id']);
        }

        return $this->prepareUsers($githubUsers);
    }

    private function getFollowingList($username, $count = null)
    {
        $following = GitHub::user()->following($username);

        $following = $count ? array_slice($following, 0, $count) : $following;
        $githubUsers = [];

        foreach ($following as $followee) {
            $githubUsers[] = GitHub::user()->showById($followee['id']);
        }

        return $this->prepareUsers($githubUsers);
    }

    private function prepareUsers($githubUsers)
    {
        $users = [];
        foreach ($githubUsers as $user) {
            [$firstName, $lastName] = $this->getName($user['name'] ?? $user['login']);

            $user['first_name'] = $firstName;
            $user['last_name'] = $lastName;
            $user['username'] = $user['login'];

            $users[] = $user;
        }

        return $users;
    }

    private function getName($fullName)
    {
        $firstName = preg_replace('/(\s*)([^\s]*)(.*)/', '$2', $fullName);
        $lastName = preg_replace('/(\s*)([^\s]*)(.*)/', '$3', $fullName);

        return [trim($firstName), trim($lastName)];
    }
}
