<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class Api
{
    private User|null $user = null;

    public function __invoke(Request $request, Closure $next)
    {
        if (!$request->bearerToken()) return $next($request);

        if (!$this->validateToken($request->bearerToken())) return $next($request);

        $this->setFollowingsInstance()->setUser($this->user);

        return $next($request);
    }

    public function validateToken(string $token): User|null
    {
        $personalAccessToken = PersonalAccessToken::findToken($token);

        $user = $personalAccessToken?->tokenable()->with([
            'followings' => fn ($query) => $query->select([
                'users.id', 'users.first_name', 'users.last_name', 'users.username',
                'followables.created_at as auth_followed_at', 'followables.accepted_at as follow_accepted_at',
            ])
        ])->first();

        return $this->user = $user;
    }

    private function setFollowingsInstance()
    {
        app()->auth_followings = $this->user->followings->keyBy('id')->map(fn ($following) => [
            'id' => $following->id,
            'username' => $following->username,
            'first_name' => $following->first_name,
            'last_name' => $following->last_name,
            'auth_followed_at' => $following->pivot->created_at,
            'follow_accepted_at' => $following->pivot->accepted_at
        ]);

        return auth();
    }
}
