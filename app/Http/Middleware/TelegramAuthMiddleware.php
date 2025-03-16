<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TelegramAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $telegramUserId = data_get($request, 'message.from.id', data_get($request, 'callback_query.from.id'));

        if ($userId = telegramAuthUser($telegramUserId)) {
            auth()->loginUsingId($userId);
        }

        return $next($request);
    }
}
