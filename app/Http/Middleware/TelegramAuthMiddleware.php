<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelegramAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $telegramUserId = $request->message['from']['id'];

        if ($userId = telegramAuthUser($telegramUserId)) {
            auth()->loginUsingId($userId);
        }

        return $next($request);
    }
}
