<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class Api
{
    public function handle(Request $request, Closure $next)
    {
        !$request->bearerToken() ?: auth()->setUser($this->validateToken($request->bearerToken()));

        return $next($request);
    }

    public function validateToken(string $token): User|null
    {
        $personalAccessToken = PersonalAccessToken::findToken($token);

        return $personalAccessToken->tokenable()->first();
    }
}
