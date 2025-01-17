<?php

namespace Database\Seeders\BlogSeeder;

use Illuminate\Database\Seeder;
use App\Models\User;
use GrahamCampbell\GitHub\Facades\GitHub;
use Throwable;

class GithubUsersSeeder extends Seeder
{
    private string $adminGithubUserName = 'amirabroun';

    public function run()
    {
        if (!config('github.connections.main.token')) {
            return;
        }

        $adminUser = User::query()->where('username', $this->adminGithubUserName)->first();

        $this->seedFollowersAndFollowingRecursively($adminUser, $this->adminGithubUserName);
    }

    private function seedFollowersAndFollowingRecursively($user, $githubUsername, $depth = 1)
    {
        if ($depth > 1) {
            return; // Limit recursion depth to avoid rate-limiting issues
        }

        foreach ($this->getFollowersList($githubUsername) as $githubUser) {
            $newUser = $this->getOrCreateUser($githubUser);

            $newUser->follow($user);

            if ($githubUser['avatar_url']) {
                $newUser->addMediaFromUrl($githubUser['avatar_url'])
                    ->usingFileName(fake()->uuid() . '.png')
                    ->toMediaCollection('avatar');
            }

            $this->seedFollowersAndFollowingRecursively($newUser, $githubUser['username'], $depth + 1);
        }

        foreach ($this->getFollowingList($githubUsername) as $githubUser) {
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

    private function getFollowersList($username)
    {
        $followers = GitHub::user()->followers($username);

        $followers = array_slice($followers, 0, 1);
        $githubUsers = [];

        foreach ($followers as $follower) {

            try {
                $githubUsers[] = GitHub::user()->showById($follower['id']);
            } catch (Throwable $e) {
                continue;
            }
        }

        return $this->prepareUsers($githubUsers);
    }

    private function getFollowingList($username)
    {
        $followings = GitHub::user()->following($username);

        $followings = array_slice($followings, 0, 1);
        $githubUsers = [];

        foreach ($followings as $following) {
            try {
                $githubUsers[] = GitHub::user()->showById($following['id']);
            } catch (Throwable $e) {
                continue;
            }
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
